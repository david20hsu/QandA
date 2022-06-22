/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : qanda

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2021-05-25 11:39:57
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for doc_file
-- ----------------------------
DROP TABLE IF EXISTS `doc_file`;
CREATE TABLE `doc_file` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `file_id` varchar(200) NOT NULL COMMENT '檔案代號',
  `file_name` varchar(255) NOT NULL COMMENT '文件名稱',
  `doc_id` varchar(2) NOT NULL COMMENT '類別',
  `doc_keyword` varchar(255) DEFAULT '' COMMENT '查詢關鍵字',
  `username` varchar(20) DEFAULT '',
  `created` datetime DEFAULT current_timestamp(),
  `updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '編修時間',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of doc_file
-- ----------------------------
INSERT INTO `doc_file` VALUES ('1', '0320191122165448.docx', '201911宅配業務員加發獎金作業', '03', '', 'david', '2019-11-22 16:54:48', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('2', '0520191125132218.docx', '客怨統計表操作說明', '05', '', 'david', '2019-11-25 13:22:18', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('3', '0520191125132557.docx', '行事曆與工作項目資料維護作業說明', '05', '', 'david', '2019-11-25 13:25:57', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('4', '0620191125161615.ppt', '配送員新人訓練2異動', '07', '', 'david', '2019-11-25 16:16:15', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('5', '0620191129164611.ppt', '配送員新人訓練1工作環境及產品介紹', '07', '', 'david', '2019-11-29 16:46:11', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('6', '0620191129164651.ppt', '配送員新人訓練3佣金及結帳篇', '07', '', 'david', '2019-11-29 16:46:51', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('7', '0420191202073839.docx', '承攬電訪員佣金與獎金說明', '04', '', 'david', '2019-12-02 07:38:39', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('8', '0620191202074144.pdf', '電訪員作業流程', '07', '', 'david', '2019-12-02 07:41:44', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('9', '0620191202074225.pptx', '電訪員退訂戶資料運用', '07', '', 'david', '2019-12-02 07:42:25', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('10', '0620191202075109.doc', '電訪員應對參考', '07', '', 'david', '2019-12-02 07:51:09', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('11', '0620191202075215.doc', '電話應對要領-品質', '07', '', 'david', '2019-12-02 07:52:15', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('12', '0620191202075636.doc', '羊奶宅配業專用名詞說明', '07', '', 'david', '2019-12-02 07:56:36', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('13', '0620191202075930.doc', '配送員客戶異動日報表說明', '07', '', 'david', '2019-12-02 07:59:30', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('14', '0120200113162837.pdf', '新人訓練3異111', '01', '', 'david', '2020-01-13 16:28:37', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('15', '0120200116103230.pdf', '201904-201912行政會議', '01', '', '600415', '2020-01-16 10:32:30', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('16', '0420200217094805.docx', '2018承攬業務員佣金與獎金說明', '04', '', 'david', '2020-02-17 09:48:05', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('17', '0420200217094834.docx', '2019業務員佣金與獎金說明', '04', '', 'david', '2020-02-17 09:48:34', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('18', '0120200217094851.docx', '2020業務員承攬佣金與獎金說明', '04', '', 'david', '2020-02-17 09:48:51', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('19', '0420200302154910.docx', '配送承攬員代送委任作業程序說明.docx', '04', '', 'david', '2020-03-02 15:49:10', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('20', '0420200302154945.docx', '配送承攬人合約終止申請作業程序.docx', '04', '', 'david', '2020-03-02 15:49:45', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('21', '0420200302155152.xls', '合約終止申請單.xls', '06', '', 'david', '2020-03-02 15:51:52', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('22', '0420200302155350.docx', '承攬電訪員佣金與獎金說明_202001', '04', '', 'david', '2020-03-02 15:53:50', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('23', '0220210518132308.pdf', '2021年5月取奶時間表', '02', '防疫作戰公約', 'david', '2021-05-18 13:23:08', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('24', '0820210520115321.pdf', '雲端發票取號程序', '08', '雲端發票、取號', 'david', '2021-05-20 11:53:21', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('25', '0620210521165503.docx', '預收款雲端發票樣張', '06', '預收款、雲端發票樣張', 'david', '2021-05-21 16:55:03', '2021-05-25 11:20:38');
INSERT INTO `doc_file` VALUES ('26', '0420210521165818.docx', '承攬電訪員勞務與酬勞說明', '04', '電訪員、酬勞、薪資 2021-02-26', 'david', '2021-05-21 16:58:18', '2021-05-25 11:20:38');

-- ----------------------------
-- Table structure for doc_type
-- ----------------------------
DROP TABLE IF EXISTS `doc_type`;
CREATE TABLE `doc_type` (
  `doc_id` varchar(3) NOT NULL,
  `doc_name` varchar(100) DEFAULT '',
  `username` varchar(30) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`doc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of doc_type
-- ----------------------------
INSERT INTO `doc_type` VALUES ('01', '會議記錄', 'david', '2021-05-25 07:25:20', '2021-05-25 07:25:20');
INSERT INTO `doc_type` VALUES ('02', '公告', 'david', '2021-05-25 07:25:54', '2021-05-25 07:25:59');
INSERT INTO `doc_type` VALUES ('03', '競賽辦法', 'david', '2021-05-25 07:26:25', '2021-05-25 07:26:25');
INSERT INTO `doc_type` VALUES ('04', '程序書', 'david', '2021-05-25 07:26:50', '2021-05-25 07:26:50');
INSERT INTO `doc_type` VALUES ('05', '作業說明', 'david', '2021-05-25 07:27:19', '2021-05-25 07:27:19');
INSERT INTO `doc_type` VALUES ('06', '表單或紀錄', 'david', '2021-05-25 07:27:46', '2021-05-25 07:27:46');
INSERT INTO `doc_type` VALUES ('07', '訓練文件', 'david', '2021-05-25 07:28:10', '2021-05-25 07:28:10');
INSERT INTO `doc_type` VALUES ('08', '專案紀錄', 'david', '2021-05-25 07:28:33', '2021-05-25 07:28:33');

-- ----------------------------
-- Table structure for qna_category
-- ----------------------------
DROP TABLE IF EXISTS `qna_category`;
CREATE TABLE `qna_category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qna_category
-- ----------------------------
INSERT INTO `qna_category` VALUES ('1', '送奶員裝備管理', 'david', '2021-05-22 09:32:43', '2021-05-22 09:34:05');
INSERT INTO `qna_category` VALUES ('2', '送奶員合約終止', 'david', '2021-05-22 09:32:43', '2021-05-22 09:34:09');
INSERT INTO `qna_category` VALUES ('3', '送奶員自我管理', 'david', '2021-05-22 09:32:43', '2021-05-23 08:18:35');

-- ----------------------------
-- Table structure for qna_posts
-- ----------------------------
DROP TABLE IF EXISTS `qna_posts`;
CREATE TABLE `qna_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `message` text NOT NULL,
  `cat_id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `status` enum('發佈','草案','封存') NOT NULL DEFAULT '發佈',
  `hits` int(11) DEFAULT 0 COMMENT '點閱次數',
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qna_posts
-- ----------------------------
INSERT INTO `qna_posts` VALUES ('1', '第一次收款作業話術範例', '1.	自我介紹：\r\n1.1	我是XXX，在嘉南羊乳擔任兼職晨間配送員，到目前為止有xx年餘的專業工作經驗。\r\n1.2	很高興能為您(府上)處理晨間羊乳配送、每月收帳的服務工作。\r\n1.3	本月是第一次來府上收款，為了防止詐騙的疑慮，本公司的每一位收款員都配帶收款名牌，以證明本人就是府上的專屬收款員。(必要時出示身份證明文件-如健保卡、駕照)\r\n2.	當月帳單傳達訊息：(先出示帳單) (範例)\r\n2.1	嘉南羊乳合作社在官方的統計資料，收乳量已佔全國的82%，是國內最大的羊乳公司。\r\n2.2	有智慧的您已選擇嘉南羊乳，是國內羊乳純度最高、品質最好的商羊乳。\r\n3.	應收帳單內容及各欄位的說明：\r\n3.1	商品名稱：飲用商品的簡稱，熱羊乳、冰洋乳、冰酪乳等三種。\r\n3.2	小計：當月各商品飲用瓶數的小計。\r\n3.3	每天(週序)的飲用量。\r\n3.4	贈瓶：本期以預收方案贈瓶沖銷的瓶數。\r\n3.5	計價瓶：小計 – 贈瓶；計算價錢的瓶數。\r\n3.6	單價：各商品的單價。\r\n3.7	上期未收：上期收款移到本月收的金額。(一定要說明上期未收的原因，之後不要再發生移到下月收的狀況)\r\n3.8	沖預收款：(各商品)本期帳款沖銷預收款的金額。\r\n3.9	應收總額：(各商品)本期應收帳款的金額 (計價瓶 * 單價 +上期未收 - 沖預收款)\r\n3.10本收據小計：各商品應收總額的小計。(就是本月要收取的金額)\r\n4.	請客戶配合作業事項：\r\n4.1	我們有客戶服務人員，是專門接聽客戶(您)的電話查詢、交辦事項，如果您有任何疑問、要配合事項、或送奶的瓶數、口味等要改變，請在提前一個工作天的上班時間(週一至週五) 08:00-16:00電話聯絡；我們將竭誠幫您服務、完成您的交待事項。\r\n4.2	因為鮮羊奶的備貨、檢奶、蒸奶等都要有準備時間，客戶任何變動都請您於前一天通知，方便我們的作業。\r\n4.3	請您盡可能以電話聯絡本公司，請不要貼單；否則時效上都會造成我們的作業困擾(例如今天不送奶、我們都已經把奶都帶來了，這樣子造成奶要報廢，很可惜；也會是我的損失)，也可能影響到其他客戶的權益。\r\n5.	提醒事項：\r\n5.1	如果客戶飲用數很少，建議改休息公式，最好天天送。\r\n話術：喝羊奶是保健兼食療，天天飲用效果更顯著。\r\n5.2	近來常發生未收到..\r\n5.3	特殊輪公式\r\n5.4	公司近期要休假(停送)提醒\r\n5.5	年節加送冰奶(7瓶)，可以宅配點交，是年節期間最佳選項。\r\n6.	離開前，記得道謝\r\n6.1	再一次謝謝您，很高興繼續為您服務；\r\n6.2	祝福您全家人，健康快樂、心想事成。', '1', 'david', '發佈', '61', '2021-05-22 09:32:43', '2021-05-24 14:48:24');
INSERT INTO `qna_posts` VALUES ('2', '工作計劃如何做 ?', '1.	工作計劃的意義：對即將開展工作的構想和安排，進而提出任務、目標、完成時間、步驟、方法等。\r\n2.	工作計劃的要求：\r\n2.1	工作計劃不是寫出來的，而是做出來的；強烈要求『說、寫、做』一致的執行力。\r\n2.2	計劃的內容遠比形式來的重要：不要華麗的詞藻，著重實實在在的內容。\r\n2.3	工作計劃要求簡明扼要、具體明確，用詞造句必須準確，不能含糊。\r\n2.4	簡單、清楚、可操作性是工作計劃要達到的基本要求。\r\n3.	工作計劃的來源與根據：\r\n3.1	個人任務與職掌；常態性、週期性、預防性及風險考量等都要兼顧，有量化的基準或時間的進度，才能達到『按質按量的如期完成任務』。\r\n3.2	主管交辦事項的展開與推動；盡可能有具體的目標、時程與可用資源，才能逐步展開、逐項落實。\r\n3.3	組織發展、業務推展或人力發展的策略延伸。\r\n3.4	必須在實踐中逐步修訂、補充以完善計劃。\r\n4.	編訂工作計劃的四大要素：\r\n4.1 工作內容(What)：做什麼；工作目標、任務。\r\n任務或要求應該具體明確，盡可能定出數量、質量、時程的要求。\r\n4.2 工作方法(How)：怎麼做；採取措施、方法、工具、程序。\r\n這是實現計劃的保證；措施和方法主要指達到既定目標需要採取什麼手段，動員哪些力量與資源，創造什麼條件，排除哪些困難等。特別是針對工作總體上存在問題的分析，擬定解決問題的方法。\r\n4.3 工作分工(Who)：誰來做；工作負責人。\r\n執行計劃的工作程序與時間安排；且在實施過程中，有輕重緩急之分。\r\n4.4工作進度(When)：什麼時間做；完成期限。\r\n在時間安排上，也要有時限，做好明訂每一個階段的完工時間要求，以及人力、物力的安排。\r\n5.	工作計劃的五大驗證基準：\r\n5.1	自我負責：寫出來的工作計劃，目的就是要執行，沒有實現的計劃，便是『虛假欺騙』。\r\n5.2	切實可行：目標是『跳起來就可以摘桃子』，不能『不用跳就能摘到桃子』,更不能『跳再高也摘不到桃子』。\r\n5.3	集思廣益：眾智成城結善緣；溝通是計劃執行不可或缺的步驟。\r\n5.4	突破重點：解決問題，必須抓著要點；打蛇打七寸。\r\n5.5	防患未然：補救措施是發生問題後的調整手段，經常查核執行情況和進度，發現問題時，就地解決並繼續前進。', '1', 'david', '發佈', '14', '2021-05-22 09:32:29', '2021-05-23 10:49:57');
INSERT INTO `qna_posts` VALUES ('3', ' 測試顯示狀況 ?', '新頭殼newtalk\r\n\r\n今傳出1名羅東鎮某電子遊樂場女員工驚傳確診！據了解，該名確診者因出現相關症狀，就醫後不見好轉後被採檢確診，已住院進行隔離治療。本刊實地調查，該名個案工作場域為一商業大樓，齊集了各類商務旅館、金融機構，且位於火車站前，是人潮聚集的地方。\r\n\r\n<p><img src=\"https://images.chinatimes.com/newsphoto/2021-05-20/1024/20210520006183.jpg\" alt=\"中央氣象局表示，明（21）日起鋒面南壓台灣北部海面，北部、東半部地區有局部短暫陣雨或雷雨，尤其午後中南部山區有局部短暫雷陣雨，午後各地山區並有局部大雨發生的機率。(本報資料照)\" width=\"821\" height=\"548\" /><br /><span style=\"font-family: \'Microsoft JhengHei\', sans-serif; font-size: 16.0002px; background-color: #ffffff;\">中央氣象局表示，明（21）日起鋒面南壓台灣北部海面，北部、東半部地區有局部短暫陣雨或雷雨，尤其午後中南部山區有局部短暫雷陣雨，午後各地山區並有局部大雨發生的機率。(本報資料照)<br /><br /></span></p>\r\n<p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 20px; font-family: \'Microsoft JhengHei\', sans-serif; font-size: 18px; background-color: #ffffff;\"><span style=\"font-size: 18pt; color: #0047ff;\"><strong>中央氣象局表示，</strong></span>明（21）日起鋒面南壓台灣北部海面，北部、東半部地區有局部短暫陣雨或雷雨，尤其午後中南部山區有局部短暫雷陣雨，午後各地山區並有局部大雨發生的機率；東南部地區有焚風發生的機率。氣象局長鄭明典昨日已搶先在臉書PO出衛星雲圖預告，觀測到紫色的團狀雲塊，代表梅雨季最常帶來的強降雨系統開始活躍起來了！</p>\r\n<div class=\"ad text-wrap-around\" style=\"box-sizing: border-box; font-size: 0px; font-family: \'Microsoft JhengHei\', sans-serif; line-height: 1.5; float: left; background-color: #ffffff;\">\r\n<div class=\"banner-336x280\" style=\"box-sizing: border-box; text-align: center; margin: 0px;\">&nbsp;</div>\r\n</div>\r\n<p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 20px; font-family: \'Microsoft JhengHei\', sans-serif; font-size: 18px; background-color: #ffffff;\">氣象局表示，未來一周都受到滯留鋒面影響，周五、周六太平洋高壓勢力稍退，各地山區午後有機會出現劇烈雨勢，不排除有局部大雨，東半部及北部地區有局部短暫雷陣雨，其他地區為多雲時晴的天氣，午後各地山區有局部短暫雷陣雨，並有局部大雨發生的機率。</p>', '2', 'david', '發佈', '22', '2021-05-22 15:12:43', '2021-05-22 15:12:43');
INSERT INTO `qna_posts` VALUES ('4', '下雨了,圖像測試', '<p><img src=\"https://images.chinatimes.com/newsphoto/2021-05-21/1024/20210521004024.jpg\" alt=\"德基水庫下午降嘩嘩啦大雨，梨山農民聽到久違滴答雨聲，紛紛衝到戶外淋雨歡呼。（民眾提供／王文吉台中傳真）\" width=\"770\" height=\"433\" /><br /><span style=\"font-family: \'Microsoft JhengHei\', sans-serif; font-size: 16.0002px; background-color: #ffffff;\">德基水庫下午降嘩嘩啦大雨，梨山農民聽到久違滴答雨聲，紛紛衝到戶外淋雨歡呼。（民眾提供／王文吉台中傳真）</span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"font-family: \'Microsoft JhengHei\', sans-serif; font-size: 18px; background-color: #ffffff;\">德基水庫今日蓄水率僅1.36％<strong>，<span style=\"background-color: #169179;\">水位降到1322.60公尺，創47年新低紀錄。今日上午梨山雲層增厚，當地果農期待普將甘霖</span>，</strong>水氣醞釀到下午3時40分終於下起大雨。梨山農民聽到久違滴答雨聲，紛紛衝到戶外淋雨歡呼，目前雨勢持續中。</span></p>', '1', 'david', '發佈', '5', '2021-05-23 08:44:19', '2021-05-24 14:48:19');
INSERT INTO `qna_posts` VALUES ('5', '鋒面大軍來了！雲圖這區紫一塊 明起雨勢狂炸', '<p><img src=\"https://images.chinatimes.com/newsphoto/2021-05-20/1024/20210520006183.jpg\" alt=\"中央氣象局表示，明（21）日起鋒面南壓台灣北部海面，北部、東半部地區有局部短暫陣雨或雷雨，尤其午後中南部山區有局部短暫雷陣雨，午後各地山區並有局部大雨發生的機率。(本報資料照)\" width=\"821\" height=\"548\" /><br /><span style=\"font-family: \'Microsoft JhengHei\', sans-serif; font-size: 16.0002px; background-color: #ffffff;\">中央氣象局表示，明（21）日起鋒面南壓台灣北部海面，北部、東半部地區有局部短暫陣雨或雷雨，尤其午後中南部山區有局部短暫雷陣雨，午後各地山區並有局部大雨發生的機率。(本報資料照)<br /><br /></span></p>\r\n<p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 20px; font-family: \'Microsoft JhengHei\', sans-serif; font-size: 18px; background-color: #ffffff;\"><span style=\"font-size: 18pt; color: #0047ff;\"><strong>中央氣象局表示，</strong></span>明（21）日起鋒面南壓台灣北部海面，北部、東半部地區有局部短暫陣雨或雷雨，尤其午後中南部山區有局部短暫雷陣雨，午後各地山區並有局部大雨發生的機率；東南部地區有焚風發生的機率。氣象局長鄭明典昨日已搶先在臉書PO出衛星雲圖預告，觀測到紫色的團狀雲塊，代表梅雨季最常帶來的強降雨系統開始活躍起來了！</p>\r\n<div class=\"ad text-wrap-around\" style=\"box-sizing: border-box; font-size: 0px; font-family: \'Microsoft JhengHei\', sans-serif; line-height: 1.5; float: left; background-color: #ffffff;\">\r\n<div class=\"banner-336x280\" style=\"box-sizing: border-box; text-align: center; margin: 0px;\">&nbsp;</div>\r\n</div>\r\n<p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 20px; font-family: \'Microsoft JhengHei\', sans-serif; font-size: 18px; background-color: #ffffff;\">氣象局表示，未來一周都受到滯留鋒面影響，周五、周六太平洋高壓勢力稍退，各地山區午後有機會出現劇烈雨勢，不排除有局部大雨，東半部及北部地區有局部短暫雷陣雨，其他地區為多雲時晴的天氣，午後各地山區有局部短暫雷陣雨，並有局部大雨發生的機率。</p>', '2', 'david', '發佈', '6', '2021-05-23 08:46:53', '2021-05-24 14:48:29');
INSERT INTO `qna_posts` VALUES ('11', '測試新修作業aaaaaa', '<p>測試新修作業</p>\r\n<p>測試新修作業</p>\r\n<p>測試新修作業</p>\r\n<p>測試新修作業</p>\r\n<p>測試新修作業</p>\r\n<p>&nbsp;</p>\r\n<p>xxxxxxxx</p>', '1', 'david', '封存', '2', '2021-05-23 13:13:31', '2021-05-24 14:51:04');
INSERT INTO `qna_posts` VALUES ('19', '展示影片功能', '<p>苗栗之美<br /><br /><iframe src=\"//www.youtube.com/embed/x8GLK2XOwsw\" width=\"560\" height=\"314\" allowfullscreen=\"allowfullscreen\"></iframe></p>\r\n<p>在看苗栗之美<br /><iframe src=\"//www.youtube.com/embed/I1hAp5yzGaQ\" width=\"560\" height=\"314\" allowfullscreen=\"allowfullscreen\"></iframe></p>', '3', 'david', '發佈', '1', '2021-05-24 16:09:02', '2021-05-24 16:09:18');

-- ----------------------------
-- Table structure for qna_user
-- ----------------------------
DROP TABLE IF EXISTS `qna_user`;
CREATE TABLE `qna_user` (
  `username` varchar(30) NOT NULL,
  `userpass` varchar(50) NOT NULL,
  `user_role` enum('訪客','寫手','測試員','審查員','管理員') DEFAULT '訪客',
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qna_user
-- ----------------------------
INSERT INTO `qna_user` VALUES ('david', '0000', '管理員', '2021-05-22 09:32:29', '2021-05-22 09:35:51');
INSERT INTO `qna_user` VALUES ('guest', '0000', '訪客', '2021-05-22 09:36:07', '2021-05-22 09:36:21');
INSERT INTO `qna_user` VALUES ('master', '0000', '審查員', '2021-05-22 09:32:29', '2021-05-22 09:35:53');
INSERT INTO `qna_user` VALUES ('tester', '0000', '測試員', '2021-05-22 09:32:29', '2021-05-22 09:35:55');
INSERT INTO `qna_user` VALUES ('writer', '0000', '寫手', '2021-05-22 09:32:29', '2021-05-22 09:35:59');

-- ----------------------------
-- Table structure for qna_user0
-- ----------------------------
DROP TABLE IF EXISTS `qna_user0`;
CREATE TABLE `qna_user0` (
  `username` varchar(30) NOT NULL,
  `userpass` varchar(100) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `visited` int(11) DEFAULT 0 COMMENT '參訪次數',
  `user_role` enum('訪客','寫手','測試員','審查員','管理員') DEFAULT '訪客',
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qna_user0
-- ----------------------------
INSERT INTO `qna_user0` VALUES ('david', '4a7d1ed414474e4033ac29ccb8653d9b', '徐禎基', '1', '管理員', '2021-05-23 16:14:57', '2021-05-24 08:43:40');
INSERT INTO `qna_user0` VALUES ('guest', '4a7d1ed414474e4033ac29ccb8653d9b', '訪客', '0', '訪客', '2021-05-23 17:18:18', '2021-05-23 17:19:14');
INSERT INTO `qna_user0` VALUES ('master', '4a7d1ed414474e4033ac29ccb8653d9b', '審查員', '0', '審查員', '2021-05-23 17:18:18', '2021-05-23 17:20:08');
INSERT INTO `qna_user0` VALUES ('tester', '96e79218965eb72c92a549dd5a330112', '測試員', '1', '測試員', '2021-05-23 17:18:18', '2021-05-24 12:01:05');
INSERT INTO `qna_user0` VALUES ('writer', '4a7d1ed414474e4033ac29ccb8653d9b', '寫手', '0', '寫手', '2021-05-23 17:18:18', '2021-05-23 17:19:51');
