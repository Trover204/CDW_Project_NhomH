<?php

namespace Database\Seeders;

use App\Models\Ad;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsAdSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $adminId = DB::table('users')->where('email', 'admin@sannhomh.vn')->value('id');

        $news = [
            [
                'title' => 'Ưu đãi 20% cho khách đặt sân buổi sáng tháng 9',
                'slug' => 'uu-dai-20-dat-san-buoi-sang-thang-9',
                'category' => 'Khuyến mãi',
                'image' => 'images/turf-bg.jpg',
                'summary' => 'Giảm 20% cho mọi đơn đặt sân trước 11h sáng trong tháng 9.',
                'content' => 'Nhằm khuyến khích tinh thần rèn luyện thể thao buổi sớm, hệ thống Sân Nhóm H triển khai chương trình khuyến mãi giảm ngay 20% giá thuê sân cho tất cả các khung giờ trước 11h00 sáng từ thứ Hai đến thứ Sáu hàng tuần. Hãy nhanh tay đặt lịch sớm để tận hưởng bầu không khí trong lành và mức giá ưu đãi nhất!',
                'views' => 150,
            ],
            [
                'title' => 'Mẹo chọn vợt cầu lông cho người mới chơi',
                'slug' => 'meo-chon-vot-cau-long-cho-nguoi-moi',
                'category' => 'Mẹo thể thao',
                'image' => 'images/caulong1.png',
                'summary' => 'Những tiêu chí cơ bản giúp bạn chọn được cây vợt phù hợp.',
                'content' => 'Đối với người mới bắt đầu chơi cầu lông, việc lựa chọn một cây vợt có trọng lượng vừa phải (khoảng 3U hoặc 4U), chu vi cán vợt G5 và điểm cân bằng trung bình là điều vô cùng quan trọng. Tránh chọn những dòng vợt quá nặng đầu vì dễ gây mỏi cơ vai và chấn thương cổ tay khi kỹ thuật đánh cầu chưa hoàn thiện.',
                'views' => 280,
            ],
            [
                'title' => 'Pickleball là gì? Luật chơi cơ bản môn thể thao mới nổi',
                'slug' => 'pickleball-la-gi-luat-choi-co-ban',
                'category' => 'Thể thao',
                'image' => 'images/image.png',
                'summary' => 'Giới thiệu môn pickleball đang phổ biến tại TP.HCM.',
                'content' => 'Pickleball là sự kết hợp độc đáo giữa quần vợt, cầu lông và bóng bàn. Với kích thước sân nhỏ hơn tennis và quả bóng nhựa có lỗ bay chậm hơn, pickleball rất dễ tiếp cận cho mọi lứa tuổi, từ thanh thiếu niên đến người lớn tuổi. Luật chơi cơ bản yêu cầu bóng nảy một lần ở mỗi bên trước khi đánh trả (two-bounce rule) và vùng kitchen không được vô-lê.',
                'views' => 410,
            ],
            [
                'title' => 'Ra mắt gói thành viên VIP giảm đến 18%',
                'slug' => 'ra-mat-goi-thanh-vien-vip',
                'category' => 'Khuyến mãi',
                'image' => 'images/turf-bg.jpg',
                'summary' => 'Đăng ký gói thành viên để được giảm giá mỗi lần đặt sân.',
                'content' => 'Sân Nhóm H chính thức giới thiệu gói thuê bao thành viên VIP với nhiều quyền lợi vượt trội: giảm giá trực tiếp đến 18% cho mỗi giờ đặt sân, ưu tiên giữ lịch vào khung giờ cao điểm và tặng kèm nước uống cùng dịch vụ giặt sấy đồ thi đấu miễn phí.',
                'views' => 320,
            ],
            [
                'title' => 'Bóng rổ - Môn thể thao mới tại Sân Nhóm H',
                'slug' => 'bong-ro-mon-the-thao-moi',
                'category' => 'Thể thao',
                'image' => 'images/image.png',
                'summary' => 'Hệ thống sân bóng rổ tiêu chuẩn đã chính thức đi vào hoạt động.',
                'content' => 'Chào đón các tay ném bóng rổ đến trải nghiệm cụm sân bóng rổ tiêu chuẩn thi đấu với sàn đệm giảm chấn cao cấp, hệ thống bảng rổ kính cường lực và dàn đèn LED chống chói mắt. Sân mở cửa từ 6h sáng đến 23h đêm tại tất cả các chi nhánh.',
                'views' => 260,
            ],
            [
                'title' => 'Hướng dẫn đặt sân và thanh toán qua VNPay',
                'slug' => 'huong-dan-dat-san-va-thanh-toan',
                'category' => 'Hướng dẫn',
                'image' => 'images/turf-bg.jpg',
                'summary' => 'Các bước đơn giản để đặt sân và thanh toán trực tuyến dễ dàng.',
                'content' => 'Chỉ với 3 bước đơn giản: Chọn sân và khung giờ mong muốn -> Điền thông tin cá nhân -> Quét mã QR VNPay trên ứng dụng ngân hàng, bạn đã hoàn tất đặt sân và nhận ngay mã đặt chỗ kèm vị trí chỉ đường chính xác qua email và số điện thoại.',
                'views' => 190,
            ],

            // ================== CÁC BÀI VIẾT THEO HÌNH ẢNH CẦU THỦ THỰC TẾ ==================
            [
                'title' => 'Lionel Messi và Đội tuyển Argentina: Đỉnh cao vinh quang của huyền thoại vĩ đại nhất lịch sử',
                'slug' => 'lionel-messi-va-argentina-dinh-cao-vinh-quang-huyen-thoai',
                'category' => 'Ngôi sao sân cỏ',
                'image' => 'images/images - 2026-10-23T144526.755.jpg',
                'summary' => 'Lionel Messi tiếp tục truyền cảm hứng cho hàng triệu người hâm mộ khắp hành tinh với tài năng thiên bẩm và tinh thần thủ lĩnh bất diệt trong màu áo sọc trắng xanh Argentina.',
                'content' => "Lionel Messi giương cao hai cánh tay chào khán giả với tấm băng thủ lĩnh và nụ cười rạng rỡ đã trở thành một trong những biểu tượng bất tử của làng túc cầu hiện đại. Trải qua muôn vàn thử thách khắc nghiệt, siêu sao người Argentina đã hoàn tất bộ sưu tập danh hiệu đồ sộ nhất mà mọi cầu thủ bóng đá hằng mơ ước.\n\nKhông chỉ mê hoặc đối thủ bằng những pha chạm bóng ma thuật, nhãn quan chiến thuật phi thường và khả năng sút phạt siêu đẳng, Messi còn là điểm tựa tinh thần vững chắc cho các đồng đội trẻ. Ở bất kỳ sân đấu nào anh đặt chân đến, El Pulga luôn cống hiến thứ bóng đá đẹp mắt, thuần khiết và thắp sáng niềm đam mê rực cháy trong trái tim người hâm mộ.",
                'views' => 1250,
            ],
            [
                'title' => 'Cristiano Ronaldo: Ý chí phi thường và khao khát chinh phục những kỷ lục bất tận',
                'slug' => 'cristiano-ronaldo-y-chi-phi-thuong-ky-luc-bat-tan',
                'category' => 'Ngôi sao sân cỏ',
                'image' => 'images/images - 2026-10-23T144523.227.jpg',
                'summary' => 'Bất chấp thời gian và tuổi tác, Cristiano Ronaldo vẫn duy trì thể trạng đỉnh cao cùng ngọn lửa đam mê rực cháy trong từng bước chạy và pha lập công.',
                'content' => "Hình ảnh Cristiano Ronaldo bật cao đánh đầu hoặc ăn mừng đầy nhiệt huyết sau mỗi pha ghi bàn đã khắc sâu vào ký ức của nhiều thế hệ yêu bóng đá. Bằng chế độ tập luyện nghiêm ngặt bậc nhất và kỷ luật sắt đá, CR7 tiếp tục phá vỡ hết kỷ lục săn bàn này đến kỷ lục săn bàn khác.\n\nTinh thần không bao giờ bỏ cuộc và khao khát chiến thắng tột cùng của Ronaldo là nguồn cảm hứng vô giá cho những cầu thủ trẻ đang theo đuổi sự nghiệp bóng đá. Dù ở màu áo câu lạc bộ hay đội tuyển quốc gia, anh luôn thể hiện đẳng cấp và sự cống hiến tuyệt đối mỗi khi xỏ giày bước ra thảm cỏ xanh.",
                'views' => 1180,
            ],
            [
                'title' => 'Erling Haaland: "Quái vật" ghi bàn hủy diệt mọi hàng phòng ngự tại Premier League',
                'slug' => 'erling-haaland-quai-vat-ghi-ban-manchester-city',
                'category' => 'Ngôi sao sân cỏ',
                'image' => 'images/images - 2026-10-23T144656.521.jpg',
                'summary' => 'Tiền đạo người Na Uy Erling Haaland tiếp tục thiết lập những chuẩn mực ghi bàn mới tại giải Ngoại hạng Anh nhờ sức mạnh vượt trội và bản năng sát thủ vòng cấm.',
                'content' => "Những pha không chiến dũng mãnh, cú tì đè uy lực và khả năng chọn vị trí thông minh của Erling Haaland khiến mọi trung vệ lão luyện tại giải Ngoại hạng Anh đều phải e dè. Tiền đạo chủ lực của Manchester City sở hữu thể hình vượt trội kết hợp cùng tốc độ bứt phá đáng kinh ngạc.\n\nMỗi trận đấu có mặt Haaland luôn là một bữa tiệc bàn thắng mãn nhãn. Sự ăn ý giữa anh và dàn tiền vệ kiến thiết tài hoa đang giúp Man City duy trì sức mạnh hủy diệt và khẳng định vị thế của một trong những tập thể hùng mạnh nhất châu Âu.",
                'views' => 990,
            ],
            [
                'title' => 'Vinicius Junior: Ngòi nổ ma thuật định đoạt các trận đại chiến của Real Madrid',
                'slug' => 'vinicius-junior-ngoi-no-ma-thuat-real-madrid',
                'category' => 'Ngôi sao sân cỏ',
                'image' => 'images/images - 2026-10-23T144628.285.jpg',
                'summary' => 'Với tốc độ xé gió cùng kỹ năng rê bóng lắt léo đậm chất ngẫu hứng Brazil, Vinicius Jr đã vươn tầm thành cầu thủ chạy cánh nguy hiểm bậc nhất thế giới.',
                'content' => "Tại thánh địa Santiago Bernabéu, Vinicius Junior luôn là nhân tố bùng nổ nhất trên hàng công Real Madrid. Khả năng rê dắt lắt léo, những cú lắc hông đổi hướng chớp nhoáng và sự tự tin đối đầu trực diện khiến các hậu vệ đối phương vô cùng vất vả trong việc đeo bám.\n\nKhông chỉ tạo đột biến bên hành lang cánh trái, Vinicius còn ngày càng hoàn thiện khả năng dứt điểm sắc bén trong những trận cầu sinh tử tại UEFA Champions League. Anh chính là biểu tượng rực rỡ cho thế hệ siêu sao mới của Los Blancos.",
                'views' => 920,
            ],
            [
                'title' => 'Bruno Fernandes: Bộ não sáng tạo và thủ lĩnh không thể thay thế tại Old Trafford',
                'slug' => 'bruno-fernandes-bo-nao-sang-tao-manchester-united',
                'category' => 'Ngôi sao sân cỏ',
                'image' => 'images/images - 2026-10-23T144625.017.jpg',
                'summary' => 'Mang trên tay tấm băng thủ lĩnh Quỷ Đỏ Manchester United, Bruno Fernandes luôn là điểm tựa tinh thần và trung tâm của mọi đường bóng tấn công sắc sảo.',
                'content' => "Mỗi khi Manchester United lâm vào thế trận khó khăn, Bruno Fernandes luôn là người xuất hiện để tung ra đường chuyền quyết định hoặc tự mình tạo nên khoảnh khắc xuất thần. Tiền vệ người Bồ Đào Nha sở hữu nhãn quan chiến thuật siêu việt và nguồn năng lượng hoạt động dồi dào suốt 90 phút thi đấu.\n\nVới chiếc áo số 8 cùng tinh thần chiến đấu không khoan nhượng, Bruno đã chứng minh vai trò không thể thay thế trong đội hình Quỷ Đỏ, dẫn dắt đội bóng vượt qua thử thách để hướng tới những danh hiệu lớn.",
                'views' => 870,
            ],
            [
                'title' => 'Đại chiến Champions League: Cuộc đọ sức đỉnh cao giữa các vì sao hàng đầu châu Âu',
                'slug' => 'dai-chien-champions-league-cuoc-do-suc-dinh-cao',
                'category' => 'Cúp Châu Âu',
                'image' => 'images/images - 2026-10-23T144632.045.jpg',
                'summary' => 'Trận thư hùng rực lửa tại đấu trường UEFA Champions League chứng kiến màn tranh chấp quyết liệt giữa những chân sút cự phách và các hòn đá tảng phòng ngự kiên cường.',
                'content' => "Những trận thư hùng giữa Real Madrid và Bayern Munich tại cúp C1 châu Âu luôn cống hiến kịch tính nghẹt thở đến giây phút cuối cùng. Màn so tài không khoan nhượng giữa trung vệ thép Antonio Rüdiger và bộ đôi tấn công Harry Kane - Jamal Musiala là minh chứng rõ nét cho đẳng cấp hàng đầu thế giới.\n\nTừng pha tranh chấp trên không, từng tình huống cản phá quyết liệt trên thảm cỏ Champions League thể hiện trọn vẹn vẻ đẹp của sức mạnh, tư duy chiến thuật và khát khao chạm tay vào chiếc cúp tai voi danh giá.",
                'views' => 840,
            ],
            [
                'title' => 'Karim Benzema và Marcelo: Những chứng nhân lịch sử làm nên kỷ nguyên hoàng kim Bernabéu',
                'slug' => 'karim-benzema-marcelo-ky-nguyen-hoang-kim-bernabeu',
                'category' => 'Bên lề sân cỏ',
                'image' => 'images/images - 2026-10-23T144709.330.jpg',
                'summary' => 'Sự ăn ý tuyệt hảo giữa Quả bóng Vàng Karim Benzema và vũ công Marcelo đã cống hiến cho người hâm mộ Real Madrid vô số khoảnh khắc kỳ diệu tại Champions League.',
                'content' => "Mỗi khi nhắc về những mùa giải vinh quang của Real Madrid tại đấu trường châu Âu, bộ đôi Karim Benzema và Marcelo luôn để lại những cảm xúc sâu sắc nhất. Tình bạn thân thiết, sự thấu hiểu trong từng bước chạy và pha phối hợp ngẫu hứng bên cánh trái đã tạo nên thương hiệu của một Real Madrid bất khả chiến bại.\n\nHình ảnh Benzema dang rộng đôi tay ăn mừng bàn thắng cùng nụ cười rạng rỡ của Marcelo sau khi chinh phục đỉnh cao châu lục sẽ mãi là di sản quý giá trong lịch sử hào hùng của đội bóng hoàng gia Tây Ban Nha.",
                'views' => 790,
            ],
            [
                'title' => 'Ousmane Dembélé và diện mạo tấn công rực lửa của Paris Saint-Germain',
                'slug' => 'ousmane-dembele-dien-mao-tan-cong-paris-saint-germain',
                'category' => 'Ngôi sao sân cỏ',
                'image' => 'images/images - 2026-10-23T144635.876.jpg',
                'summary' => 'Khả năng thuận cả hai chân và tăng tốc bùng nổ của Ousmane Dembélé đang biến hành lang cánh của đội bóng thủ đô nước Pháp thành mối đe dọa thường trực.',
                'content' => "Trong màu áo Paris Saint-Germain, Ousmane Dembélé đang thể hiện phong độ thăng hoa bậc nhất sự nghiệp. Tốc độ xuất phát cực nhanh, những cú đảo chân tinh quái và kỹ thuật dứt điểm bằng cả chân trái lẫn chân phải giúp Dembélé dễ dàng xé toang các hàng phòng ngự đối phương.\n\nDưới bàn tay chiến thuật của ban huấn luyện, Dembélé không chỉ tự mình ghi bàn mà còn tạo ra hàng loạt cơ hội ngon ăn cho các đồng đội, giúp lối chơi tấn công của PSG trở nên biến hóa và khó lường hơn bao giờ hết.",
                'views' => 760,
            ],
            [
                'title' => 'Ousmane Dembélé tỏa sáng rực rỡ trong màu áo Đội tuyển Quốc gia Pháp',
                'slug' => 'ousmane-dembele-toa-sang-doi-tuyen-phap',
                'category' => 'Đội tuyển quốc gia',
                'image' => 'images/images - 2026-10-23T144640.681.jpg',
                'summary' => 'Màn trình diễn thăng hoa cùng những pha ăn mừng đầy cảm xúc của Dembélé khẳng định sức mạnh chiều sâu đáng sợ của Những chú gà trống Gô-loa.',
                'content' => "Mỗi khi khoác lên mình màu áo Đội tuyển Pháp, Dembélé luôn ra sân với tinh thần chiến đấu cao độ và sự nhiệt huyết mãnh liệt. Những pha bứt tốc dọc hành lang biên của anh luôn mở ra chìa khóa gỡ nút thắt trong những trận cầu cam go tại các giải đấu lớn như World Cup và Euro.\n\nPha ăn mừng dang tay đầy kiêu hãnh của Dembélé trước sự cuồng nhiệt của hàng vạn cổ động viên là minh chứng cho sự trưởng thành và vị trí ngày càng vững chắc của anh trong đội hình Les Bleus.",
                'views' => 730,
            ],
            [
                'title' => 'Khoảnh khắc nâng cúp vinh quang: Phần thưởng xứng đáng cho tinh thần đồng đội quả cảm',
                'slug' => 'khoanh-khac-nang-cup-vinh-quang-tinh-than-dong-doi',
                'category' => 'Cúp Châu Âu',
                'image' => 'images/images - 2026-10-23T144620.170.jpg',
                'summary' => 'Niềm hân hoan vỡ òa của các ngôi sao khi cùng nhau giương cao chiếc cúp vô địch sau chặng đường thi đấu quả cảm và cống hiến hết mình.',
                'content' => "Khoảnh khắc các cầu thủ cùng nhau giương cao chiếc cúp bạc vô địch trên bục vinh quang luôn là hình ảnh đẹp đẽ và xúc động nhất trong thể thao. Đằng sau tấm huy chương vàng lấp lánh là hàng ngàn giờ tập luyện đổ mồ hôi, những chấn thương vượt qua bằng ý chí và sự đoàn kết một lòng của toàn thể đội bóng.\n\nTiếng reo hò cuồng nhiệt từ người hâm mộ và nụ cười rạng rỡ của các cầu thủ nâng cao chiếc cúp vô địch khẳng định chân lý: Thành công trên sân cỏ luôn bắt nguồn từ tinh thần tập thể gắn kết bền chặt.",
                'views' => 810,
            ],
            [
                'title' => 'Khvicha Kvaratskhelia: Hiện tượng sân cỏ Đông Âu và hành trình vượt khó đáng nể',
                'slug' => 'khvicha-kvaratskhelia-hien-tuong-san-co-dong-au',
                'category' => 'Ngôi sao sân cỏ',
                'image' => 'images/images - 2026-10-23T144700.675.jpg',
                'summary' => 'Tinh thần chiến đấu bền bỉ và phẩm chất thiên tài đã giúp Kvaratskhelia chinh phục trái tim người hâm mộ khắp lục địa già.',
                'content' => "Được mệnh danh là 'Kvaradona', Khvicha Kvaratskhelia là hình mẫu tiêu biểu cho tài năng nở muộn nhưng bùng nổ dữ dội nhờ nghị lực phi thường. Dù phải đối mặt với những pha phạm lỗi thô bạo và chấn thương đau đớn trên sân, tiền vệ người Georgia chưa từng chùn bước.\n\nLối đá giàu tính đột biến, kỹ năng đi bóng trực diện và sự hy sinh vì tập thể giúp Kvaratskhelia trở thành viên ngọc quý của bóng đá châu Âu đương đại.",
                'views' => 750,
            ],
            [
                'title' => 'Bukayo Saka dẫn đầu làn sóng các đại diện bóng đá Anh khuynh đảo đấu trường châu lục',
                'slug' => 'bukayo-saka-lan-song-bong-da-anh-dau-truong-chau-luc',
                'category' => 'Thể thao',
                'image' => 'images/images - 2026-10-23T144647.394.jpg',
                'summary' => 'Sự thăng tiến vượt bậc của Bukayo Saka cùng các ngôi sao Premier League tại các cúp châu Âu chứng minh sức hấp dẫn tột cùng của bóng đá xứ sở sương mù.',
                'content' => "Sự hiện diện ấn tượng của Bukayo Saka trong màu áo Arsenal cùng các câu lạc bộ Anh tại các cúp châu Âu tiếp tục khẳng định sức mạnh vượt trội của giải Ngoại hạng. Saka đại diện cho lối chơi bóng hiện đại: tốc độ, thông minh, khiêm nhường và đầy hiệu quả.\n\nCùng với các đồng nghiệp như Ollie Watkins, Saka đang chứng minh bóng đá Anh sở hữu thế hệ cầu thủ bản địa tài năng, sẵn sàng cạnh tranh sòng phẳng ở mọi đấu trường lớn nhất châu lục.",
                'views' => 820,
            ],
            [
                'title' => 'Sự trở lại ấn tượng của Aston Villa trên bản đồ bóng đá đỉnh cao châu Âu',
                'slug' => 'su-tro-lai-an-tuong-aston-villa-dau-truong-chau-au',
                'category' => 'Cúp Châu Âu',
                'image' => 'images/images - 2026-10-23T144510.964.jpg',
                'summary' => 'Lối chơi kỷ luật, tốc độ và đầy nhiệt huyết giúp các cầu thủ Aston Villa tạo nên những bất ngờ ngoạn mục trước các đối thủ sừng sỏ.',
                'content' => "Sau nhiều năm chờ đợi, câu lạc bộ giàu truyền thống Aston Villa đã trở lại đấu trường UEFA Champions League với phong độ cực kỳ ấn tượng. Màn trình diễn lăn xả của các cầu thủ trên sân khách trước các đối thủ khó chịu cho thấy sự chuẩn bị chiến thuật kỹ lưỡng của HLV Unai Emery.\n\nSự tự tin, khả năng tranh chấp bóng quyết liệt và tinh thần chiến đấu không khoan nhượng của dàn sao Aston Villa đang biến họ thành chú ngựa ô đáng gờm tại cúp C1 mùa này.",
                'views' => 680,
            ],
            [
                'title' => 'Lứa măng non Bồ Đào Nha: Thế hệ kế cận đầy hứa hẹn tiếp bước các đàn anh',
                'slug' => 'lua-mang-non-bo-dao-nha-the-he-ke-can',
                'category' => 'Đội tuyển quốc gia',
                'image' => 'images/images - 2026-10-23T144714.435.jpg',
                'summary' => 'Các tài năng trẻ Bồ Đào Nha với kỹ thuật điêu luyện và sự gắn kết chặt chẽ đang sẵn sàng bùng nổ trên đấu trường quốc tế.',
                'content' => "Nền bóng đá Bồ Đào Nha chưa bao giờ thiếu vắng những tài năng kiệt xuất. Lứa cầu thủ trẻ kế cận với nền tảng đào tạo bài bản từ các lò danh tiếng như Sporting, Benfica, Porto đang liên tục gặt hái những chiến thắng vang dội ở các giải trẻ châu lục.\n\nTinh thần đồng đội gắn kết và kỹ thuật xử lý bóng thanh thoát giúp thế hệ măng non Bồ Đào Nha tự tin tiếp nối những di sản rực rỡ mà Cristiano Ronaldo và các thế hệ tiền bối đã gây dựng.",
                'views' => 640,
            ],
            [
                'title' => 'Nguyễn Quang Hải: Đẳng cấp ngôi sao và những khoảnh khắc làm nức lòng hàng triệu trái tim',
                'slug' => 'nguyen-quang-hai-dang-cap-ngoi-sao-tuyen-viet-nam',
                'category' => 'Bóng đá Việt Nam',
                'image' => 'images/images - 2026-10-23T144531.846.jpg',
                'summary' => 'Tiền vệ tài hoa mang áo số 19 vẫn luôn là linh hồn và niềm hy vọng lớn của người hâm mộ trong mỗi đợt tập trung của Đội tuyển Quốc gia Việt Nam.',
                'content' => "Hình ảnh Nguyễn Quang Hải tự tin chỉnh đốn chiếc áo đấu số 19 quen thuộc trước khi bước vào những trận đánh lớn đã trở nên vô cùng quen thuộc với người hâm mộ bóng đá Việt Nam. Cái chân trái ma thuật của Quang Hải luôn tiềm ẩn khả năng định đoạt trận đấu bằng những siêu phẩm đá phạt hoặc đường chuyền xé toang hàng thủ đối phương.\n\nDù trải qua những nốt trầm trong sự nghiệp, bản lĩnh ngôi sao và cái duyên trong những trận cầu quyết định vẫn giúp Quang Hải giữ vững vị trí đặc biệt trong lòng người hâm mộ nước nhà.",
                'views' => 1350,
            ],
            [
                'title' => 'Nguyễn Đình Bắc tỏa sáng tại V-League: Tự tin khẳng định bản lĩnh tài năng trẻ',
                'slug' => 'nguyen-dinh-bac-toa-sang-vleague-cong-an-ha-noi',
                'category' => 'Bóng đá Việt Nam',
                'image' => 'images/images - 2026-10-23T144751.557.jpg',
                'summary' => 'Pha ăn mừng ấn tượng cùng phong độ thăng hoa trong màu áo CLB Công An Hà Nội cho thấy sự trưởng thành vượt bậc của chân sút trẻ Đình Bắc.',
                'content' => "Khoác lên mình số áo 9 tại CLB Công An Hà Nội, tiền đạo trẻ Nguyễn Đình Bắc đang chứng tỏ sự tiến bộ vượt bậc sau những màn trình diễn thăng hoa ở cả cấp độ câu lạc bộ lẫn đội tuyển quốc gia. Tốc độ, khả năng dứt điểm quyết đoán và lối chơi dũng cảm giúp Đình Bắc luôn tạo ra sự đột biến cao trên hàng công.\n\nPha ăn mừng đặt tay lên môi đầy tự tin của Đình Bắc trên sân Hàng Đẫy đã truyền đi thông điệp mạnh mẽ về khát vọng vươn lên khẳng định mình của thế hệ cầu thủ trẻ Việt Nam.",
                'views' => 1120,
            ],
            [
                'title' => 'Đội tuyển Việt Nam thăng hoa: Tinh thần chiến binh và những bàn thắng mãn nhãn',
                'slug' => 'doi-tuyen-viet-nam-thang-hoa-tinh-than-chien-binh',
                'category' => 'Bóng đá Việt Nam',
                'image' => 'images/images - 2026-10-23T144814.576.jpg',
                'summary' => 'Khoảnh khắc toàn đội tuyển Việt Nam vỡ òa ăn mừng bàn thắng khẳng định ý chí chiến đấu ngoan cường và quyết tâm cống hiến vì màu cờ sắc áo.',
                'content' => "Hình ảnh Phạm Tuấn Hải, Bùi Hoàng Việt Anh, Nguyễn Văn Tùng và các tuyển thủ Việt Nam cùng ôm nhau ăn mừng cuồng nhiệt sau một bàn thắng đẳng cấp luôn làm bùng cháy khán đài. Tinh thần chiến binh quật cường, sự lăn xả hết mình trong từng pha tranh chấp giúp Những chiến binh sao vàng luôn nhận được tình yêu thương vô điều kiện từ hàng triệu người hâm mộ.\n\nMỗi trận đấu của Đội tuyển Việt Nam là một minh chứng hùng hồn cho sự kiên định, đoàn kết và khát khao đưa bóng đá nước nhà vươn xa hơn nữa trên đấu trường châu lục.",
                'views' => 1290,
            ],
            [
                'title' => 'Những cô gái kim cương Việt Nam: Tinh thần kiên cường viết nên trang sử vẻ vang',
                'slug' => 'co-gai-kim-cuong-doi-tuyen-nu-viet-nam-lich-su',
                'category' => 'Bóng đá Việt Nam',
                'image' => 'images/images - 2026-10-23T144744.352.jpg',
                'summary' => 'Đội tuyển bóng đá nữ quốc gia tiếp tục là niềm tự hào lớn của thể thao nước nhà với chuỗi thành tích ấn tượng và tinh thần thi đấu quả cảm.',
                'content' => "Huỳnh Như, Nguyễn Thị Thanh Nhã, Bích Thùy, Tuyết Dung... những cái tên thân thương ấy đã khắc sâu vào lịch sử thể thao nước nhà bằng ý chí quật cường và sự hy sinh thầm lặng. Vượt qua mọi rào cản và định kiến, các cô gái vàng của bóng đá Việt Nam đã nhiều lần thống trị đấu trường SEA Games và bước ra sân chơi World Cup rực rỡ.\n\nNụ cười rạng rỡ cùng những cái nắm tay thật chặt sau mỗi chiến thắng của các nữ tuyển thủ là tấm gương sáng ngời về nghị lực, tinh thần phụ nữ Việt Nam kiên trung và bất khuất.",
                'views' => 1160,
            ],
            [
                'title' => 'Khoảnh khắc rợp cờ đỏ sao vàng: Hào khí bóng đá Việt Nam trên đấu trường quốc tế',
                'slug' => 'khoanh-khac-rop-co-do-sao-vang-hao-khi-viet-nam',
                'category' => 'Bóng đá Việt Nam',
                'image' => 'images/images - 2026-10-23T144758.119.jpg',
                'summary' => 'Hình ảnh các nữ tuyển thủ cầm lá cờ Tổ quốc chạy quanh sân trong tiếng reo hò của người hâm mộ là biểu tượng tuyệt đẹp của tinh thần dân tộc.',
                'content' => "Giây phút các cầu thủ cầm lá cờ đỏ sao vàng phấp phới chạy quanh sân vận động sau trận chung kết nghẹt thở, giữa biển cờ và tiếng hô vang 'Việt Nam vô địch' của hàng vạn cổ động viên, là khoảnh khắc chạm đến trái tim của mỗi người dân đất Việt.\n\nBóng đá không chỉ là môn thể thao đơn thuần, mà còn là sợi dây thiêng liêng kết nối triệu triệu con tim, khơi dậy lòng tự hào dân tộc và ý chí vươn lên không ngừng nghỉ của thế hệ trẻ hôm nay.",
                'views' => 1210,
            ],
            [
                'title' => 'Khuất Văn Khang và lứa cầu thủ U23: Tương lai xán lạn của bóng đá nước nhà',
                'slug' => 'khuat-van-khang-lua-cau-thu-u23-viet-nam',
                'category' => 'Bóng đá Việt Nam',
                'image' => 'images/images - 2026-10-23T144519.402.jpg',
                'summary' => 'Những bước chạy nhiệt huyết, pha ăn mừng giàu cảm xúc của Văn Khang và các đồng đội trẻ mở ra niềm tin mãnh liệt cho người hâm mộ bóng đá Việt Nam.',
                'content' => "Khoác áo số 8 nơi tuyến giữa, Khuất Văn Khang cùng các đồng đội ở đội tuyển trẻ đang thể hiện sự chững chạc và tư duy chơi bóng hiện đại. Từng pha thoát pressing, những cú đá phạt chuẩn xác và khả năng điều tiết trận đấu nhịp nhàng giúp Văn Khang khẳng định vai trò trụ cột của thế hệ kế cận.\n\nSự tự tin khi thi đấu sòng phẳng trước các đối thủ hàng đầu châu lục của các tuyển thủ trẻ là tín hiệu đáng mừng cho tương lai đầy triển vọng của bóng đá Việt Nam.",
                'views' => 980,
            ],
            [
                'title' => 'Đêm pháo hoa rực rỡ: Tuyển trẻ Việt Nam giương cao cúp vô địch Đông Nam Á',
                'slug' => 'dem-phao-hoa-ruc-ro-tuyen-tre-viet-nam-vo-dich',
                'category' => 'Bóng đá Việt Nam',
                'image' => 'images/images - 2026-10-23T144806.606.jpg',
                'summary' => 'Chiến thắng thuyết phục tại trận chung kết giải trẻ khu vực mang lại niềm vui bất tận cùng danh hiệu vô địch danh giá cho bóng đá trẻ Việt Nam.',
                'content' => "Dưới màn mưa pháo hoa rực rỡ và những dải ruy băng lấp lánh, các cầu thủ trẻ Việt Nam đã cùng nhau nâng cao chiếc cúp vô địch Đông Nam Á trong niềm hạnh phúc vỡ òa. Thành quả ngọt ngào này là minh chứng xứng đáng cho công tác đào tạo trẻ bài bản và sự nỗ lực không biết mệt mỏi của ban huấn luyện cùng các học trò.\n\nChiếc cúp vô địch là bước đệm vững chắc giúp các cầu thủ trẻ có thêm sự tự tin để hướng tới những sân chơi lớn hơn ở tầm châu lục.",
                'views' => 1040,
            ],
            [
                'title' => 'Khát vọng tuổi trẻ: Tinh thần đoàn kết làm nên sức mạnh của các lứa trẻ Việt Nam',
                'slug' => 'khat-vong-tuoi-tre-tinh-than-doan-ket-tuyen-tre-vn',
                'category' => 'Bóng đá Việt Nam',
                'image' => 'images/images - 2026-10-23T144733.860.jpg',
                'summary' => 'Sự nỗ lực không ngừng nghỉ trong tập luyện và thi đấu giúp các cầu thủ trẻ ngày càng hoàn thiện bản lĩnh trận mạc trên sân cỏ.',
                'content' => "Cái ôm thật chặt, vòng tay quây quần ăn mừng bàn thắng của toàn đội bóng trẻ thể hiện trọn vẹn tinh thần đồng đội keo sơn. Ở lứa tuổi đôi mươi tràn đầy nhiệt huyết, các em luôn bước ra sân với tinh thần thi đấu hết mình, không ngại va chạm và luôn tôn trọng đối thủ.\n\nChính tinh thần đoàn kết và ý thức kỷ luật cao độ là bệ phóng giúp các cầu thủ trẻ ngày càng trưởng thành, sẵn sàng gánh vác trọng trách tại đội tuyển quốc gia trong tương lai gần.",
                'views' => 880,
            ],
            [
                'title' => 'Bùng nổ cảm xúc: Những khoảnh khắc ăn mừng bàn thắng thăng hoa của các tuyển thủ trẻ',
                'slug' => 'bung-no-cam-xuc-khoanh-khac-an-mung-ban-thang-tuyen-tre',
                'category' => 'Bóng đá Việt Nam',
                'image' => 'images/images - 2026-10-23T144726.580.jpg',
                'summary' => 'Từng bàn thắng đẹp mắt, từng cái ôm thắt chặt tinh thần đồng đội tiếp thêm động lực to lớn cho hành trình vươn tầm châu lục của bóng đá Việt Nam.',
                'content' => "Không gì phấn khích bằng cảm giác lưới rung lên và toàn đội bóng cùng lao về phía góc khán đài ăn mừng cuồng nhiệt cùng người hâm mộ. Những khoảnh khắc thăng hoa ấy là kết tinh của hàng ngàn giọt mồ hôi trên sân tập, sự thấu hiểu chiến thuật và khao khát khẳng định bản thân.\n\nNiềm vui chiến thắng của các tuyển thủ trẻ hôm nay chính là ngọn lửa thắp sáng niềm tin và kỳ vọng của người hâm mộ vào một kỷ nguyên mới rực rỡ hơn nữa của bóng đá Việt Nam.",
                'views' => 890,
            ],
        ];

        // Clean up old news items that are not in the current list
        $currentSlugs = array_column($news, 'slug');
        DB::table('news')->whereNotIn('slug', $currentSlugs)->delete();

        foreach ($news as $item) {
            DB::table('news')->updateOrInsert(
                ['slug' => $item['slug']],
                [
                    'title' => $item['title'],
                    'content' => $item['content'],
                    'summary' => $item['summary'],
                    'image' => $item['image'],
                    'category' => $item['category'],
                    'user_id' => $adminId,
                    'views' => $item['views'],
                    'status' => 'published',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $ads = [
            ['Banner khuyến mãi tháng 9', '/promo', 'Giảm 20% khung giờ sáng'],
            ['Banner gói thành viên',     '/plans', 'Đăng ký VIP giảm đến 18%'],
        ];
        DB::table('ads')->delete();
        foreach ($ads as [$name, $link, $desc]) {
            DB::table('ads')->insert([
                'name' => $name, 'link_url' => $link, 'description' => $desc,
                'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        // Fake 20 additional ads
        Ad::factory()->count(20)->create();
    }
}
