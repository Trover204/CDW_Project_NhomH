<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use RuntimeException;

/**
 * Lưu / xóa file upload một cách an toàn, chống tấn công Path Traversal.
 *
 * - Chỉ cho phép thao tác trong các thư mục đã khai báo (whitelist).
 * - Tên file luôn được sinh ngẫu nhiên (hashName), không dùng tên gốc do client gửi lên.
 * - Trước khi xóa, đường dẫn lưu trong DB phải khớp đúng định dạng "thư-mục/tên-file"
 *   (không chứa "..", "/" đầu, "\", null byte, thư mục con...). Đường dẫn không hợp lệ sẽ bị bỏ qua.
 */
class SecureFileStorage
{
    public const DISK = 'public';

    /**
     * Danh sách thư mục được phép lưu / xóa file trên disk public.
     *
     * @var list<string>
     */
    public const ALLOWED_DIRECTORIES = [
        'ads',
        'avatars',
        'news',
        'products',
        'sport-types',
    ];

    /**
     * Lưu file upload vào thư mục cho phép với tên ngẫu nhiên.
     */
    public function store(UploadedFile $file, string $directory): string
    {
        $this->assertAllowedDirectory($directory);

        // store() dùng hashName(): tên ngẫu nhiên 40 ký tự + phần mở rộng đoán từ MIME,
        // không bao giờ dùng getClientOriginalName() nên không thể chèn "../" vào tên file.
        $path = $file->store($directory, self::DISK);

        if (! is_string($path) || ! $this->isSafePath($path, $directory)) {
            throw new RuntimeException('Không thể lưu file tải lên.');
        }

        return $path;
    }

    /**
     * Xóa file nếu và chỉ nếu đường dẫn nằm gọn trong thư mục cho phép.
     */
    public function delete(?string $path, string $directory): bool
    {
        if ($path === null || $path === '') {
            return false;
        }

        if (! $this->isSafePath($path, $directory)) {
            Log::warning('Blocked unsafe file deletion (possible path traversal).', [
                'path' => $path,
                'directory' => $directory,
            ]);

            return false;
        }

        $disk = Storage::disk(self::DISK);

        return $disk->exists($path) && $disk->delete($path);
    }

    /**
     * Thay file cũ bằng file mới: lưu file mới trước, sau đó mới xóa file cũ (an toàn).
     */
    public function replace(UploadedFile $file, ?string $oldPath, string $directory): string
    {
        $newPath = $this->store($file, $directory);

        if ($oldPath !== null && $oldPath !== $newPath) {
            $this->delete($oldPath, $directory);
        }

        return $newPath;
    }

    /**
     * Kiểm tra đường dẫn có dạng "<directory>/<filename>" hợp lệ hay không.
     */
    public function isSafePath(?string $path, string $directory): bool
    {
        if (! is_string($path) || $path === '' || ! $this->isAllowedDirectory($directory)) {
            return false;
        }

        if (str_contains($path, "\0") || str_contains($path, '\\') || str_contains($path, '..')) {
            return false;
        }

        // Modifier "D" để "$" không khớp với ký tự xuống dòng ở cuối chuỗi.
        $pattern = '#^'.preg_quote($directory, '#').'/[A-Za-z0-9][A-Za-z0-9_-]*(\.[A-Za-z0-9]{1,10})?$#D';

        return preg_match($pattern, $path) === 1;
    }

    public function isAllowedDirectory(string $directory): bool
    {
        return in_array($directory, self::ALLOWED_DIRECTORIES, true);
    }

    private function assertAllowedDirectory(string $directory): void
    {
        if (! $this->isAllowedDirectory($directory)) {
            throw new InvalidArgumentException("Thư mục lưu trữ không được phép: {$directory}");
        }
    }
}
