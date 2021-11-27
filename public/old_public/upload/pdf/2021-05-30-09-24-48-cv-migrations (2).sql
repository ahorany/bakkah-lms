-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 27, 2021 at 11:08 AM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bakkah_learning_db1`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(9, '2014_10_12_000000_create_users_table', 4),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2020_08_27_091714_create_posts_table', 1),
(5, '2020_02_11_070024_create_seos_table', 2),
(6, '2020_02_11_070715_create_postkeywords_table', 2),
(7, '2020_02_11_070828_create_seokeywords_table', 2),
(8, '2019_10_30_071106_create_uploads_table', 3),
(44, '2020_09_03_100927_create_post_morphs_table', 13),
(30, '2020_09_07_123819_create_training_options_table', 6),
(49, '2020_09_09_104148_create_accordions_table', 15),
(53, '2020_09_07_104743_create_courses_table', 16),
(54, '2020_09_13_093525_create_details_table', 16),
(46, '2020_09_10_073010_create_partners_table', 14),
(55, '2020_09_14_074935_create_testimonials_table', 14),
(56, '2020_09_15_063417_create_sessions_table', 14),
(57, '2020_09_15_104031_add_url_to_posts_table', 14),
(58, '2020_09_16_090418_create_carts_table', 14),
(59, '2020_09_18_194408_add_take2_price_to_courses', 14),
(60, '2020_09_30_083625_add_certification_column_to_courses_table', 17),
(61, '2020_10_01_064636_add_education_or_consluting_columns_to_partners_table', 17),
(62, '2020_10_25_074113_create_cart_traces_table', 18),
(63, '2020_10_30_183725_create_jobs_table', 18),
(64, '2020_11_01_092626_create_retarget_discounts_table', 18),
(65, '2020_11_08_102458_create_services_table', 19),
(66, '2020_10_20_061624_create_careers_table', 20),
(67, '2020_11_08_131433_create_service_archives_table', 21),
(68, '2020_11_19_103118_create_course_interests_table', 21),
(69, '2020_12_09_104743_create_reports_table', 21),
(70, '2020_12_13_084634_create_webinars_table', 21),
(71, '2020_12_14_114814_create_webinars_registrations_table', 21),
(72, '2020_12_23_133940_create_discount_countries_table', 21),
(73, '2021_01_12_143920_create_organizations_table', 22),
(74, '2021_01_27_123909_create_group_invoice_masters_table', 22),
(75, '2021_01_31_103413_create_notes_table', 21),
(76, '2021_02_11_092542_add_two_factor_code_to_users_table', 23),
(77, '2021_02_17_060331_create_b2b_masters_table', 23),
(78, '2021_02_17_123236_create_cart_features_table', 23),
(79, '2021_02_18_133449_create_options_table', 23),
(80, '2021_02_22_143549_add_training_option_feature_id_to_cart_features_table', 23),
(81, '2021_03_01_132703_create_cart_masters_table', 23),
(82, '2021_03_01_141514_add_cart_masters_to_carts_table', 23),
(83, '2021_03_01_231743_add__token_to_carts_table', 23),
(84, '2021_03_03_101436_create_attendants_table', 23),
(85, '2021_03_03_105703_create_user_from_excels_table', 23),
(86, '2021_03_08_134403_create_wishlists_table', 23),
(87, '2021_03_09_103303_add_totals_to_group_invoice_masters_table', 23),
(88, '2021_03_09_161535_add_coin_to_group_invoice_masters_table', 23),
(89, '2021_03_10_143102_add_sesion_id_to_wishlists_table', 23),
(90, '2021_03_14_140754_create_socials_table', 23),
(91, '2021_04_04_150129_create_exams_table', 23),
(92, '2021_04_04_150806_create_questions_table', 23),
(93, '2021_04_04_151045_create_answers_table', 23),
(94, '2021_05_09_120932_create_messages_table', 24),
(98, '2021_05_26_105613_create_profiles_table', 25),
(101, '2021_05_27_132004_add_developer_id_to_sessions_table', 26);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
