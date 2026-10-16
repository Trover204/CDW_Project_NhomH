# Quy tắc phát triển Laravel cho AI Agent

- **Framework:** Laravel 13 , PHP 8.3+.
- **Database & Model:**
  - Mọi thay đổi bảng phải thông qua Migration (`database/migrations`).
  - Viết đầy đủ `$fillable` hoặc `$guarded`, và định nghĩa quan hệ (Relationships) kèm return type.
  - Luôn tạo Factory và Seeder tương ứng khi tạo Model mới.
- **Validation & Request:**
  - Không validate trực tiếp trong Controller; luôn tách ra `FormRequest` (`app/Http/Requests`).
- **Testing:**
  - Mỗi feature mới bắt buộc phải có Feature Test (`tests/Feature/`).
  - Dùng Pest (hoặc PHPUnit) với trait `RefreshDatabase`.
- **Formatting:** Tuân thủ PSR-12 (có thể chạy qua `./vendor/bin/pint`).