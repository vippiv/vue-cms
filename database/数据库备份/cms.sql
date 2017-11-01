-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2017-10-27 07:19:00
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cms`
--

-- --------------------------------------------------------

--
-- 表的结构 `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `id` smallint(255) NOT NULL AUTO_INCREMENT,
  `cname` varchar(1000) NOT NULL,
  `website` varchar(1000) NOT NULL,
  `mobilephone` varchar(1000) NOT NULL,
  `telephone` varchar(1000) NOT NULL,
  `email` varchar(1000) NOT NULL,
  `qq` varchar(1000) NOT NULL,
  `note` varchar(1000) NOT NULL,
  `createAt` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `customer`
--

INSERT INTO `customer` (`id`, `cname`, `website`, `mobilephone`, `telephone`, `email`, `qq`, `note`, `createAt`) VALUES
(1, '苏州谢谢网络', 'http://www.szxiexie.com', '15371453318', '0512-6618 0312', 'xiexie@szxiexie.com 							', '470211273', '', '0000-00-00'),
(2, '上海华夕网络科技有限公司', 'http://www.yjhlw.net/', '17349788820', '', '345920555@qq.com', '345920555', '', '0000-00-00'),
(3, '上海史特信息技术有限公司', 'http://www.01website.cn/', '13764208198', '021-68093520', '', '', '', '0000-00-00'),
(4, '上海七通网络科技股份有限公司', 'http://www.shqtn.com/', '', '021-50688671', '', '', '上海七通网络科技股份有限公司（总部）总机电话：021-50688671    联系地址：上海浦东新区张杨路707号生命人寿大厦2501室\n七通 苏州联系电话：0512-67217806    联系地址：苏州姑苏区万达广场c座2403\n七通 台湾联系地址：台北市大同区郑州路87号7楼', '0000-00-00'),
(5, '铭心科技', 'http://www.shpd.com/', '', '', '', '', '400-021-9801', '0000-00-00'),
(6, '云网客', 'http://www.51baping.net/', '', '', '', '', '', '0000-00-00'),
(7, '上海逐天网络', 'http://www.021wangluo.com/', '13817873536', '021-52841056', 'sun0208run@yahoo.com.cn', '', '', '0000-00-00'),
(8, '大道网络（上海）股份有限公司', 'http://www.dartop.net/', '', '021-50800588', '', '', '', '0000-00-00');

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` smallint(255) NOT NULL AUTO_INCREMENT,
  `username` varchar(1000) NOT NULL,
  `passward` varchar(1000) NOT NULL,
  `createAt` date NOT NULL,
  `ability` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `username`, `passward`, `createAt`, `ability`) VALUES
(1, 'admin', 'tlkj101025', '2017-10-02', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
