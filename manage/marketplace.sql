-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 26, 2025 lúc 11:35 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `marketplace`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `created_at`) VALUES
(1, 2, '2025-06-25 17:49:53'),
(2, 1, '2025-06-26 14:27:36'),
(3, 3, '2025-06-26 16:26:29');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `quantity`) VALUES
(14, 1, 2, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `support` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `comments`
--

INSERT INTO `comments` (`id`, `product_id`, `user_id`, `content`, `created_at`, `support`) VALUES
(38, 1, 1, 'người cao tuổi uống có sao không', '2025-06-26 15:59:02', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `created_at`) VALUES
(1, 2, 110000.00, '2025-06-25 18:04:34'),
(2, 2, 30000.00, '2025-06-25 18:05:17'),
(3, 2, 15000.00, '2025-06-25 18:07:03'),
(4, 2, 15000.00, '2025-06-25 18:07:38'),
(5, 2, 15000.00, '2025-06-25 18:09:21'),
(6, 2, 60000.00, '2025-06-25 18:21:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 20000.00),
(2, 1, 4, 1, 15000.00),
(3, 1, 7, 4, 15000.00),
(4, 1, 2, 1, 15000.00),
(5, 2, 2, 1, 15000.00),
(6, 2, 3, 1, 15000.00),
(7, 3, 2, 1, 15000.00),
(8, 4, 2, 1, 15000.00),
(9, 5, 2, 1, 15000.00),
(10, 6, 4, 4, 15000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product`
--

INSERT INTO `product` (`id`, `name`, `price`, `description`, `image_url`, `category`, `created_at`) VALUES
(1, 'Trà Sữa Trân Châu đường đen', 20000.00, 'THƯƠNG HIỆU: HILLWAY. XUẤT XỨ: VIỆT NAM. TRỌNG LƯỢNG TỊNH: 232G. HƯỚNG DẪN SỬ DỤNG: XEM CỤ THỂ TRÊN BAO BÌ SẢN PHẨM. HẠN SỬ DỤNG: 18 THÁNG KỂ TỪ NGÀY SẢN XUẤT. BẢO QUẢN: NƠI KHÔ RÁO, THOÁNG MÁT, TRÁNH ÁNH NẮNG TRỰC TIẾP.', '../products/image/tra-sua-tran-chau-duong-den.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(2, 'Cà phê sữa đá', 15000.00, 'Cà phê pha phin đậm đặc, hòa cùng sữa đặc và đá viên. Vị đắng quyện với vị ngọt béo, là \"quốc hồn quốc túy\" của người Việt.', '../products/image/ca-phe-sua-da.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(3, 'Cà phê đen đá', 15000.00, 'Cà phê nguyên chất không đường, đậm vị, dành cho người thích sự tỉnh táo và nguyên bản.', '../products/image/ca-phe-den-da.webp', 'Đồ Uống', '0000-00-00 00:00:00'),
(4, 'Trà đá', 15000.00, 'Trà pha loãng, uống lạnh với đá. Thức uống miễn phí quốc dân ở quán ăn Việt Nam.', '../products/image/tra-da.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(5, 'Trà chanh', 20000.00, 'Trà xanh pha với nước cốt chanh và đường. Thức uống ưa thích của học sinh, sinh viên, đặc biệt vào mùa hè.', '../products/image/tra-chanh.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(6, 'Trà tắc', 15000.00, 'Kết hợp trà xanh với nước tắc, thêm chút mật ong hoặc đường và đá lạnh, vị chua ngọt sảng khoái.', '../products/image/tra-tac.webp', 'Đồ Uống', '0000-00-00 00:00:00'),
(7, 'Number One', 15000.00, 'Nước tăng lực nội địa, vị ngọt, có gas nhẹ, giúp tỉnh táo tạm thời. Cạnh tranh với Red Bull.', '../products/image/number-one.webp', 'Đồ Uống', '0000-00-00 00:00:00'),
(8, 'Sting Dâu', 15000.00, 'Nước tăng lực hương dâu, màu đỏ bắt mắt, vị ngọt đậm, khá phổ biến trong giới trẻ.', '../products/image/sting-dau.webp', 'Đồ Uống', '0000-00-00 00:00:00'),
(9, 'C2 Trà xanh', 15000.00, 'Trà xanh đóng chai vị ngọt nhẹ, dễ uống, giá rẻ. Có các vị như chanh, đào, tắc.', '../products/image/c2-tra-xanh.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(10, 'Revive', 15000.00, 'Nước điện giải, thường dùng sau khi vận động. Có vị chua nhẹ, mặn mặn do chứa muối khoáng.', '../products/image/revive.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(11, 'Trà sữa trân châu', 20000.00, 'Trà pha cùng sữa (hoặc bột sữa), thêm trân châu dẻo dai. Có nhiều vị như matcha, socola, oolong, v.v.', '../products/image/tra-sua-tran-chau.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(12, 'Sinh tố bơ', 20000.00, 'Bơ xay với sữa đặc và đá, cực kỳ béo, mịn và mát lạnh. Một món khoái khẩu mùa hè.', '../products/image/sinh-to-bo.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(13, 'Nước mía', 10000.00, 'Nước ép từ cây mía tươi, đôi khi kèm tắc. Ngọt tự nhiên, giải nhiệt rất tốt.', '../products/image/nuoc-mia.png', 'Đồ Uống', '0000-00-00 00:00:00'),
(14, 'Đá me', 10000.00, 'Nước me chua ngọt, thêm đá, muối, đậu phộng\n', '../products/image/da-me.webp', 'Đồ Uống', '0000-00-00 00:00:00'),
(15, 'Matcha latte ', 35000.00, 'Trà xanh Nhật Bản pha với sữa tươi béo', '../products/image/matcha-latte.jpg\n', 'Đồ Uống', '0000-00-00 00:00:00'),
(16, 'Cacao sữa đá', 35000.00, 'Bột cacao pha với sữa đặc và đá', '../products/image/cacao-sua-da.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(17, '7up', 15000.00, 'Nước ngọt có gas vị chanh\n', '../products/image/7up.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(18, 'Pepsi', 15000.00, 'Nước ngọt có gas vị coca', '../products/image/pepsi.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(19, 'Red bull', 20000.00, 'Nước tăng lực, ngọt, hơi gắt\n', '../products/image/red-bull.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(20, 'Trà đào cam sả', 30000.00, 'Trà thơm, có miếng đào, cam và sả', '../products/image/tra-dao-cam-sa.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(21, 'Trà vải\n', 30000.00, 'Trà đen/oolong pha với siro vải và trái vải', '../products/image/tra-vai.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(22, 'Sữa bắp', 20000.00, 'Sữa nấu từ bắp ngọt, thơm, béo nhẹ', '../products/image/sua-bap.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(23, 'Sữa đậu nành mát', 20000.00, 'Sữa đậu nành mát, ngọt nhẹ, uống lạnh\n', '../products/image/sua-dau-nanh-mat.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(24, 'Sữa tươi trân châu đường đen ', 35000.00, 'Sữa tươi kèm trân châu ngọt đậm\n', '../products/image/sua-tuoi-tran-chau-duong-den.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(25, 'Trà sen vàng', 25000.00, 'Trà hoa nhài thanh mát, kèm hạt sen', '../products/image/tra-sen-vang.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(26, 'Trà trái cây nhiệt đới', 35000.00, 'Trà trái cây mix cam, chanh, đào, thảo mộc', '../products/image/tra-trai-cay-nhiet-doi.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(27, 'Sâm bí đao\n', 20000.00, 'Nước nấu từ bí đao, lá nếp, mía lau, uống mát, thanh', '../products/image/sam-bi-dao.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(28, 'Milo dầm trân châu ', 35000.00, 'Milo pha đậm, thêm đá, sữa đặc và topping trân châu\n', '../products/image/milo-dam-tran-chau.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(29, 'trà chôm chôm ', 40000.00, 'Tươi mát, ngọt dịu, thanh nhẹ, thơm lạ, chua nhẹ, sảng khoái, độc đáo, lôi cuốn, mát lạnh, tự nhiên', '../products/image/tra-chom-chom.png', 'Đồ Uống', '0000-00-00 00:00:00'),
(30, 'Matcha latte chuối', 45000.00, 'Matcha latte hương chuối là sự kết hợp hài hòa giữa vị trà xanh nhẹ đắng và vị ngọt dịu, thơm mát của chuối chín.', '../products/image/mattcha-latte-chuoi.png', 'Đồ Uống', '0000-00-00 00:00:00'),
(31, ' Matcha latte việt quất', 45000.00, 'Matcha latte việt quất mang đến sự hòa quyện độc đáo giữa vị trà xanh tươi mát và vị chua ngọt nhẹ nhàng của việt quất.', '../products/image/mattcha-latte-viet-quat.png', 'Đồ Uống', '0000-00-00 00:00:00'),
(32, 'Trà nhãn', 20000.00, 'Trà nhãn ngọt thanh, thơm nhẹ, kết hợp vị trà dịu và nhãn tươi, mang lại cảm giác thư giãn, dễ chịu.', '../products/image/tra-nhan.png', 'Đồ Uống', '0000-00-00 00:00:00'),
(33, 'Trà măng cụt', 22000.00, 'Trà măng cụt thanh mát, ngọt dịu, hòa quyện hương trái cây nhiệt đới và vị trà nhẹ, tạo cảm giác tươi mới.', '../products/image/tra-mang-cut.png', 'Đồ Uống', '0000-00-00 00:00:00'),
(34, 'Trà dưa lưới', 25000.00, 'Trà dưa lưới ngọt mát, thơm dịu, kết hợp vị trà nhẹ và hương dưa lưới tươi, sảng khoái, dễ uống.', '../products/image/tra-dua-luoi.png', 'Đồ Uống', '0000-00-00 00:00:00'),
(35, 'Trà xoài chanh dây', 30000.00, 'Trà xoài chanh dây chua ngọt hài hòa, thơm mát, vị trái cây nhiệt đới tươi mới, giúp giải khát và tỉnh táo.', '../products/image/tra-xoai-chanh-day.png', 'Đồ Uống', '0000-00-00 00:00:00'),
(36, 'Trà dâu', 25000.00, 'Trà dâu ngọt dịu, thơm mát, kết hợp vị trà nhẹ và dâu tươi mọng, mang lại cảm giác tươi trẻ, dễ chịu.', '../products/image/tra-dau.png', 'Đồ Uống', '0000-00-00 00:00:00'),
(37, 'Trà me muối ớt ', 25000.00, 'Trà me muối ớt chua cay mặn ngọt độc đáo, kích thích vị giác, thơm lừng, cực kỳ cuốn hút và giải khát hiệu quả.', '../products/image/tra-me-muoi-ot.png', 'Đồ Uống', '0000-00-00 00:00:00'),
(38, 'Trà nho', 25000.00, 'Trà nho ngọt thanh, thơm mát, hòa quyện hương nho tươi và vị trà nhẹ, mang lại cảm giác sảng khoái, dễ chịu.', '../products/image/tra-nho.png', 'Đồ Uống', '0000-00-00 00:00:00'),
(39, 'Trà sữa thái xanh', 25000.00, 'Trà sữa Thái xanh là sự hòa quyện giữa trà xanh Thái Lan đậm đà và sữa béo thơm lừng. ', '../products/image/tra-sua-thai-xanh.webp', 'Đồ Uống', '0000-00-00 00:00:00'),
(40, 'Trà sữa thái đỏ', 25000.00, 'Loại trà sữa đặc trưng của Thái Lan với màu đỏ cam bắt mắt, mang hương vị trà đen nồng nàn pha lẫn với vị sữa ngọt dịu.', '../products/image/tra-sua-thai-do.webp', 'Đồ Uống', '0000-00-00 00:00:00'),
(41, 'Soda chanh', 25000.00, 'Thức uống tươi mát được pha từ nước chanh tươi và soda có ga, thêm chút đường hoặc mật ong để điều vị.', '../products/image/soda-chanh.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(42, 'Soda việt quất', 25000.00, 'Soda việt quất có màu tím đẹp mắt, hương vị chua ngọt tự nhiên từ siro việt quất kết hợp với nước soda có ga tạo cảm giác mới lạ.', '../products/image/soda-viet-quat.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(43, 'Cà phê muối', 20000.00, 'Cà phê đen pha muối nhẹ, đậm đà, hậu vị béo mặn nhẹ lạ miệng.', '../products/image/ca-phe-muoi.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(44, 'Nước ép cam', 20000.00, 'Cam tươi nguyên chất, vị chua ngọt, bổ sung vitamin C.', '../products/image/nuoc-ep-cam.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(45, 'Nước ép dứa', 20000.00, 'Dứa chín ép lạnh, vị chua ngọt dịu nhẹ, thanh mát.', '../products/image/nuoc-ep-dua.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(46, 'Sinh tố xoài', 25000.00, 'Xoài chín xay mịn, ngọt đậm, mát lạnh, dễ uống.', '../products/image/sinh-to-xoai.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(47, 'Sinh tố dâu', 25000.00, 'Dâu tây tươi xay cùng sữa, màu hồng đẹp, vị chua ngọt dịu.', '../products/image/sinh-to-dau.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(48, 'Trà hoa cúc', 25000.00, 'Nước hoa cúc thanh mát, dịu nhẹ, dễ chịu, giúp thư giãn.', '../products/image/tra-hoa-cuc.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(49, 'Trà sữa phô mai tươi', 30000.00, 'Vị trà sữa béo ngậy kết hợp với lớp kem phô mai mặn mặn, thơm ngon.', '../products/image/tra-sua-pho-mai-tuoi.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(50, 'Trà sữa Oreo', 30000.00, 'Trà sữa truyền thống kết hợp vụn bánh Oreo tạo vị giòn nhẹ và béo bùi.', '../products/image/tra-sua-oreo.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(51, 'Trà đào hồng trà macchiato', 30000.00, 'Hồng trà đậm đà kết hợp đào ngâm và lớp kem mặn phía trên.', '../products/image/tra-dao-hong-tra-macchiato.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(52, 'Sữa chua nếp cẩm', 30000.00, 'Vị chua nhẹ của sữa chua quyện với nếp cẩm dẻo thơm, rất được ưa chuộng', '../products/image/sua-chua-nep-cam.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(53, 'Sữa chua việt quất', 30000.00, 'Mát lạnh với vị chua ngọt tự nhiên, phù hợp ngày nóng.', '../products/image/sua-chua-viet-quat.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(54, 'Cam sả mật ong', 30000.00, 'Vị cam chua ngọt dịu nhẹ với hương thơm sả đặc trưng.', '../products/image/cam-sa-mat-ong.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(55, 'socola bạc hà', 30000.00, 'Thức uống mùa hè với socola đậm vị và cảm giác the mát của bạc hà.', '../products/image/socola_bac_ha.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(56, 'Trà sữa than tre', 30000.00, 'Độc đáo với màu đen tự nhiên, kết hợp vị béo ngậy từ sữa', '../products/image/tra-sua-than-tre.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(57, 'Detox chanh sả mật ong', 30000.00, 'Hỗn hợp thanh lọc cơ thể, ngon và tốt cho tiêu hóa.', '../products/image/detox-chanh-sa-mat-ong.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(58, 'Trà sữa matcha đậu đỏ', 30000.00, 'Hương matcha nhẹ nhàng kết hợp đậu đỏ ngọt bùi.', '../products/image/tra-sua-matcha-dau-do.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(59, 'Trà sữa bạc hà', 25000.00, 'Vị béo nhẹ của sữa kết hợp với hương bạc hà mát lạnh, mang lại cảm giác tươi mới dễ chịu.', '../products/image/tra-sua-bac-ha.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(60, 'Trà hoa đậu biếc mật ong', 25000.00, 'Màu xanh tím tự nhiên từ hoa đậu biếc, kết hợp mật ong ngọt dịu, tốt cho sức khỏe.', '../products/image/tra-hoa-dau-biec-mat-ong.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(61, 'Trà sữa khoai môn', 30000.00, 'Màu tím đẹp mắt, hương thơm bùi bùi, vị béo nhẹ từ khoai môn và sữa.', '../products/image/tra-sua-khoai-mon.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(62, 'Sữa tươi kem trứng', 30000.00, 'Lớp kem trứng béo ngậy phủ trên sữa tươi mát lạnh, tạo vị mịn màng độc đáo.', '../products/image/sua-tuoi-kem-trung.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(63, 'Sinh tố mãng cầu', 25000.00, 'Mãng cầu chín xay mịn, chua ngọt dịu, giải nhiệt và tốt cho hệ tiêu hóa.', '../products/image/sinh-to-mang-cau.png', 'Đồ Uống', '0000-00-00 00:00:00'),
(64, 'Nước ép ổi hồng', 20000.00, 'Vị ngọt thanh, thơm nhẹ đặc trưng của ổi hồng, giàu vitamin C, tốt cho da.', '../products/image/nuoc-ep-oi-hong.jpeg', 'Đồ Uống', '0000-00-00 00:00:00'),
(65, 'Soda dâu tây', 25000.00, 'Sủi bọt mát lạnh kết hợp vị chua ngọt của dâu tây, thích hợp dùng khi trời nóng.', '../products/image/soda-dau-tay.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(66, 'Cacao đá xay', 30000.00, 'Cacao nguyên chất xay nhuyễn với đá và sữa, đậm đà, thơm béo, kích thích vị giác.', '../products/image/cacao-da-xay.jpg', 'Đồ Uống', '0000-00-00 00:00:00'),
(67, 'Trà thanh long đỏ', 25000.00, 'Màu sắc bắt mắt từ thanh long đỏ, vị ngọt thanh mát, phù hợp cho người ăn uống lành mạnh.', '../products/image/tra-thanh-long-do.png', 'Đồ Uống', '0000-00-00 00:00:00'),
(68, 'Sữa chua xoài', 25000.00, 'Sự kết hợp giữa xoài chín ngọt và sữa chua lên men, tạo vị chua ngọt hài hòa dễ uống.', '../products/image/sua-chua-xoai.jpg', 'Đồ Uống', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ho_ten` varchar(100) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `ho_ten`, `role`) VALUES
(1, 'hoainhan', '1231', 'd.hoainhan256@gmail.com', 'Hoai Nhan', 'admin'),
(2, 'nhan123', '12', 'bocleechan@gmail.com', 'Đào Hoài Nhân', 'user'),
(3, 'nhan11', '123', 'agha@gmail.com', 'Đẹp Trai Thì Có Gì Sai', 'user');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Các ràng buộc cho bảng `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
