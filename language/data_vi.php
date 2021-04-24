<?php

/**
 * @Project Module Attendance
 * @Author ChungNT (chung_vuitinh@yahoo.com)
 * @Createdate 25-2-2016
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

/**
 * Note:
 * - Module var is: $lang, $module_file, $module_data, $module_upload, $module_theme, $module_name
 * - Accept global var: $db, $db_config, $global_config
 */

//Nhom ly do
/* $sth = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_topic (id, name, weight) VALUES (?, ?, ?)");
$sth->execute(array('1', 'Lương sản phẩm', 1));
$sth->execute(array('2', 'Lương thời gian', 2));
$sth->execute(array('3', 'Ngừng việc', 3));
$sth->execute(array('4', 'Nghỉ có lý do', 4));
$sth->execute(array('5', 'Nghỉ vô lý do', 5));
$sth->execute(array('6', 'Nghỉ hưởng BHXH', 6)); */

//Ly do
$sth = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_catalogue (code, name, weight, pid) VALUES (?, ?, ?, ?)");
$sth->execute(array('K', 'Lương sản phẩm', 1, 1));
$sth->execute(array('2K', 'Lương sản phẩm 2', 2, 1));
$sth->execute(array('3K', 'Lương sản phẩm 3', 3, 1));
$sth->execute(array('P', 'Nghỉ phép', 4, 2));
$sth->execute(array('Pt', 'Phép năm trước', 5, 2));
$sth->execute(array('H', 'Học, hội nghị', 6, 2));
$sth->execute(array('R', 'Nghỉ hưởng lương', 7, 2));
$sth->execute(array('T', 'Tai nạn lao động', 8, 2));
$sth->execute(array('L', 'Nghỉ lễ', 9, 2));
$sth->execute(array('ĐD', 'Điều dưỡng', 10, 2));
$sth->execute(array('VN', 'Văn nghệ', 11, 2));
$sth->execute(array('TT', 'Thể thao', 12, 2));
$sth->execute(array('QS', 'Tập quân sự', 13, 2));
$sth->execute(array('N', 'Ngừng việc', 14, 3));
$sth->execute(array('Ro', 'Nghỉ không lương', 15, 4));
$sth->execute(array('NB', 'Nghỉ bù', 16, 4));
$sth->execute(array('NP', 'Nghỉ phiên', 17, 4));
$sth->execute(array('O', 'Nghỉ vô lý do', 18, 5));
$sth->execute(array('OO', 'Ốm', 19, 6));
$sth->execute(array('Cô', 'Con ốm', 20, 6));
$sth->execute(array('TS', 'Thai sản', 21, 6));

//Bac luong
$sth = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_level (name, weight) VALUES (?, ?)");
$sth->execute(array('Bậc 1', 1));
$sth->execute(array('Bậc 2', 2));
$sth->execute(array('Bậc 3', 3));
$sth->execute(array('Bậc 4', 4));
$sth->execute(array('Bậc 5', 5));
$sth->execute(array('Bậc 6', 6));
$sth->execute(array('Bậc 7', 7));
$sth->execute(array('Bậc 8', 8));

//Don vi
$sth = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_department(code, name, weight) VALUES (?, ?, ?)");
$sth->execute(array('BGĐ','Ban Giám đốc Công ty',1));
$sth->execute(array('KC 2/4','Ban QLDA Khe Chàm 2/4 Công ty',2));
$sth->execute(array('KTN','Đội KTN Công ty',3));
$sth->execute(array('ĐĐT','Khối Đảng đoàn thể',4));
$sth->execute(array('BQ','Phòng Bảo vệ, quân sự Công ty',5));
$sth->execute(array('CĐVT','Phòng CĐVT Công ty',6));
$sth->execute(array('ĐT','Phòng Đầu tư, môi trường Công ty',7));
$sth->execute(array('ĐTM','Phòng ĐTM Công ty',8));
$sth->execute(array('KCS','Phòng KCS và tiêu thụ Công ty',9));
$sth->execute(array('KH','Phòng Kế hoạch và Quản trị chi phí Công ty',10));
$sth->execute(array('KHVT','Phòng Kế hoạch Vật tư và Quản trị chi phí Công ty',11));
$sth->execute(array('KT','Phòng Kế toán, thống kê, tài chính',12));
$sth->execute(array('KTTC','Phòng KTTC Công ty',13));
$sth->execute(array('AT','Phòng Kỹ thuật An toàn và BHLĐ',14));
$sth->execute(array('KCM','Phòng Kỹ thuật công nghệ mỏ Công ty',15));
$sth->execute(array('TB','Phòng TB Công ty',16));
$sth->execute(array('TPK','Phòng Thanh tra, pháp chế và kiểm toán nội bộ Công ty',17));
$sth->execute(array('TCLĐ','Phòng Tổ chức - Lao động tiền lương Công ty',18));
$sth->execute(array('TĐ','Phòng Trắc địa, địa chất Công ty',19));
$sth->execute(array('VT','Phòng Vật tư Công ty',20));
$sth->execute(array('TYT','Trạm Y tế Công ty',21));
$sth->execute(array('TTĐHSX','Trung tâm ĐHSX Công ty',22));
$sth->execute(array('VP','Văn phòng Công ty',23));
$sth->execute(array('ĐXD','Đội xây dựng Công ty',24));
$sth->execute(array('ĐXC','Đội xe ca Công ty',25));
$sth->execute(array('TGĐK-KT','Đội TGĐK - KT',26));
$sth->execute(array('ĐXM','Đội xe máy Công ty',27));
$sth->execute(array('NĐS','Ngành ĐS Công ty',28));
$sth->execute(array('CĐ-CT','Phân xưởng CĐ - CT',29));
$sth->execute(array('CĐ-HR','Phân xưởng CĐ - HR',30));
$sth->execute(array('CĐ-KT','Phân xưởng CĐ - KT',31));
$sth->execute(array('CĐ-TL','Phân xưởng CĐ - TL',32));
$sth->execute(array('ĐL1-TL','Phân xưởng ĐL1 - TL',33));
$sth->execute(array('ĐL2-TL','Phân xưởng ĐL2 - TL',34));
$sth->execute(array('KT1-CT','Phân xưởng KT1 - CT',35));
$sth->execute(array('KT1-HR','Phân xưởng KT1 - HR',36));
$sth->execute(array('KT1-KT','Phân xưởng KT1 - KT',37));
$sth->execute(array('KT1-TL','Phân xưởng KT1 - TL',38));
$sth->execute(array('KT2-CT','Phân xưởng KT2 - CT',39));
$sth->execute(array('KT2-HR','Phân xưởng KT2 - HR',40));
$sth->execute(array('KT2-TL','Phân xưởng KT2 - TL',41));
$sth->execute(array('KT3-CT','Phân xưởng KT3 - CT',42));
$sth->execute(array('KT3-HR','Phân xưởng KT3 - HR',43));
$sth->execute(array('KT3-TL','Phân xưởng KT3 - TL',44));
$sth->execute(array('KT4-CT','Phân xưởng KT4 - CT',45));
$sth->execute(array('KT4-HR','Phân xưởng KT4 - HR',46));
$sth->execute(array('KT5-CT','Phân xưởng KT5 - CT',47));
$sth->execute(array('KT5-HR','Phân xưởng KT5 - HR',48));
$sth->execute(array('KT5-KT','Phân xưởng KT5 - KT',49));
$sth->execute(array('KT5-TL','Phân xưởng KT5 - TL',50));
$sth->execute(array('KT6-HR','Phân xưởng KT6 - HR',51));
$sth->execute(array('KT6-KT','Phân xưởng KT6 - KT',52));
$sth->execute(array('KT6-TL','Phân xưởng KT6 - TL',53));
$sth->execute(array('KT7-HR','Phân xưởng KT7 - HR',54));
$sth->execute(array('KT7-TL','Phân xưởng KT7 - TL',55));
$sth->execute(array('KT8-TL','Phân xưởng KT8 - TL',56));
$sth->execute(array('LT-HR','Phân xưởng KTLT - HR',57));
$sth->execute(array('LT-KT','Phân xưởng KTLT - KT',58));
$sth->execute(array('STCB-HR','Phân xưởng STCB - HR',59));
$sth->execute(array('STCB-TL','Phân xưởng STCB - TL',60));
$sth->execute(array('TGK-CT','Phân xưởng TGK-CT',61));
$sth->execute(array('TGK-HR','Phân xưởng TGK-HR',62));
$sth->execute(array('TGK-KT','Phân xưởng TGK-KT',63));
$sth->execute(array('TGK-TL','Phân xưởng TGK-TL',64));
$sth->execute(array('VTL-HR','Phân xưởng VTL - HR',65));
$sth->execute(array('VTL-TL','Phân xưởng VTL - TL',66));
$sth->execute(array('VTST-CT','Phân xưởng VTST - CT',67));

//Group
$sth = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_group (code, name, weight) VALUES (?, ?, ?)");
$sth->execute(array('01', 'Tổ quản lý', 1));
$sth->execute(array('02', 'Tổ Đảng ủy', 2));
$sth->execute(array('03', 'Tổ Công đoàn', 3));
$sth->execute(array('04', 'Tổ Đoàn thanh niên', 4));
$sth->execute(array('07', 'Tổ quản lý bếp VP', 5));
$sth->execute(array('08', 'Tổ chuyên viên', 6));
$sth->execute(array('08.', 'Ban quản lý chung cư', 7));
$sth->execute(array('09', 'Tổ văn thư', 8));
$sth->execute(array('10', 'Tổ giám sát an toàn', 9));
$sth->execute(array('11', 'Tổ TT-KCS', 10));
$sth->execute(array('12', 'Tổ kho', 11));
$sth->execute(array('13', 'Tổ xe con PV', 12));
$sth->execute(array('14', 'Tổ QL xe ca', 13));
$sth->execute(array('15', 'Tổ xe ca', 14));
$sth->execute(array('15.', 'Tổ sửa chữa xe ca', 15));
$sth->execute(array('16', 'Tổ y tế', 16));
$sth->execute(array('17', 'Tổ điện nước VP', 17));
$sth->execute(array('18', 'Tổ căng tin - dịch vụ', 18));
$sth->execute(array('19', 'Tổ tạp vụ hành chính', 19));
$sth->execute(array('20', 'Tổ bảo vệ', 20));
$sth->execute(array('21', 'Tổ bảo vệ KV Hà Ráng', 21));
$sth->execute(array('22', 'Tổ bảo vệ KV Khe Tam', 22));
$sth->execute(array('23', 'Tổ bảo vệ KV Cẩm Thành', 23));
$sth->execute(array('24', 'Tổ bảo vệ KV Tân Lập', 24));
$sth->execute(array('27', 'Tổ khoan tháo nước', 25));
$sth->execute(array('28', 'Tổ quan trắc+đóng cửa gió', 26));
$sth->execute(array('29', 'Tổ thông gió - đo khí', 27));
$sth->execute(array('30', 'Tổ điện thoại nội bộ', 28));
$sth->execute(array('31', 'Tổ cơ điện lò', 29));
$sth->execute(array('32', 'Tổ thợ lò', 30));
$sth->execute(array('33', 'Tổ vận tải lò', 31));
$sth->execute(array('34', 'Tổ sàng tuyển', 32));
$sth->execute(array('35', 'Tổ phục vụ - phụ trợ NL', 33));
$sth->execute(array('36', 'Tổ phục vụ - phụ trợ HL', 34));
$sth->execute(array('37', 'Tổ bảo vệ tiêu thụ than', 35));
$sth->execute(array('39', 'Quản lý bếp HR', 36));
$sth->execute(array('40', 'Quản lý bếp KT', 37));
$sth->execute(array('41', 'Quản lý bếp CT', 38));
$sth->execute(array('42', 'Quản lý bếp TL+PV', 39));
$sth->execute(array('43', 'Bếp ăn KV Hà Ráng', 40));
$sth->execute(array('44', 'Bếp ăn KV Khe Tam', 41));
$sth->execute(array('45', 'Bếp ăn KV Cẩm Thành', 42));
$sth->execute(array('46', 'Bếp ăn KV Tân Lập', 43));
$sth->execute(array('47', 'Tổ cấp dưỡng VP', 44));
$sth->execute(array('48', 'Dọn VS+lọc nước', 45));
$sth->execute(array('49', 'Tổ cấp dưỡng chung cư', 46));
$sth->execute(array('50', 'Tổ lái xe TP', 47));
$sth->execute(array('51', 'Tổ tiếp phẩm', 48));
$sth->execute(array('52', 'Tổ thống kê lộ thiên', 49));
$sth->execute(array('54', 'Tổ nổ mìn lộ thiên', 50));
$sth->execute(array('55', 'Tổ xe tải số 1', 51));
$sth->execute(array('56', 'Tổ xe tải số 2 ', 52));
$sth->execute(array('57', 'Tổ máy xúc', 53));
$sth->execute(array('58', 'Tổ máy gạt', 54));
$sth->execute(array('59', 'Tổ xe nước', 55));
$sth->execute(array('60', 'Tổ xe cơm ,vật tư', 56));
$sth->execute(array('61', 'Tổ đưa cơm ca2.3', 57));
$sth->execute(array('62', 'Tổ PV xe cẩu', 58));
$sth->execute(array('63', 'Tổ cơ điện-PV', 59));
$sth->execute(array('64', 'Tổ sửa chữa', 60));
$sth->execute(array('65', 'Nhóm sửa chữa TB', 61));
$sth->execute(array('66', 'Tổ bơm nước', 62));
$sth->execute(array('67', 'Tổ XD - đóng chèn HR', 63));
$sth->execute(array('68', 'Tổ XD - đóng chèn KT', 64));
$sth->execute(array('69', 'Tổ XD - đóng chèn CT', 65));
$sth->execute(array('70', 'Tổ XD - đóng chèn TL', 66));
$sth->execute(array('71', 'Tổ bốc xếp', 67));
$sth->execute(array('73', 'Tổ trạm mạng+quạt', 68));
$sth->execute(array('74', 'Tổ giặt sấy', 69));
$sth->execute(array('75', 'Tổ trạm mạng', 70));
$sth->execute(array('76', 'Tổ nhà đèn', 71));
$sth->execute(array('77', 'Tổ trạm quạt', 72));
$sth->execute(array('78', 'Tổ trạm nén+TD-25', 73));
$sth->execute(array('79', 'Tổ nhóm tiện', 74));
$sth->execute(array('81', 'Tổ sắt', 75));
$sth->execute(array('83', 'Tổ VH nồi hơi-bơm nước-tắm CN', 76));
$sth->execute(array('84', 'Tổ gia công cơ khí', 77));
$sth->execute(array('85', 'Tổ cơ khí - sửa chữa ', 78));
$sth->execute(array('86', 'Tổ sửa chữa điện', 79));
$sth->execute(array('91', 'Tổ hợp đồng Hà Ráng I', 80));
$sth->execute(array('92', 'Tổ hợp đồng Khe Tam I', 81));
$sth->execute(array('93', 'Tổ hợp đồng Cẩm Thành I', 82));
$sth->execute(array('94', 'Tổ hợp đồng Tân Lập I', 83));

//Position
$sth = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_position (code, name, weight) VALUES (?, ?, ?)");
$sth->execute(array('100','Giám đốc',1));
$sth->execute(array('101','Phó Giám đốc',2));
$sth->execute(array('102','Phó GĐ dự án KC 2/4',3));
$sth->execute(array('103','Kế toán trưởng',4));
$sth->execute(array('104','Trưởng ban Đảng',5));
$sth->execute(array('105','Bí thư đảng ủy',6));
$sth->execute(array('106','Phó Bí thư ĐU',7));
$sth->execute(array('107','Chánh VP Đảng ủy',8));
$sth->execute(array('108','Chủ tịch Công đoàn',9));
$sth->execute(array('109','Phó Chủ tịch CĐ',10));
$sth->execute(array('110','Trưởng ban Công đoàn',11));
$sth->execute(array('111','Bí thư ĐTN',12));
$sth->execute(array('112','Phó BT ĐTN',13));
$sth->execute(array('113','Trưởng phòng',14));
$sth->execute(array('114','Phó phòng',15));
$sth->execute(array('115','Quản đốc',16));
$sth->execute(array('116','Phó Quản đốc',17));
$sth->execute(array('117','Phó QĐ Cơ điện',18));
$sth->execute(array('118','Trưởng ngành',19));
$sth->execute(array('119','Phó trưởng ngành',20));
$sth->execute(array('120','Trưởng trạm',21));
$sth->execute(array('121','Phó trưởng trạm',22));
$sth->execute(array('122','Đội trưởng',23));
$sth->execute(array('123','Đội phó',24));
$sth->execute(array('124','Trưởng phòng - Trưởng TTĐHSX',25));
$sth->execute(array('125','Phó phòng - Phó TTĐHSX',26));
$sth->execute(array('126','Phó phòng - Trưởng KVSX',27));
$sth->execute(array('131','Chuyên viên',28));
$sth->execute(array('132','Phó trưởng KVSX',29));
$sth->execute(array('133','Thủ quỹ',30));
$sth->execute(array('134','Đốc công',31));
$sth->execute(array('135','Cán bộ trực ca',32));
$sth->execute(array('137','Nhân viên lưu trữ',33));
$sth->execute(array('138','Nhân viên kinh tế',34));
$sth->execute(array('139','Nhân viên thống kê',35));
$sth->execute(array('140','Nhân viên Kỹ thuật',36));
$sth->execute(array('141','Trực ca NĐS',37));
$sth->execute(array('142','Phụ trách nhà ăn',38));
$sth->execute(array('143','Nhân viên tiếp liệu',39));
$sth->execute(array('144','Thủ kho VLN',40));
$sth->execute(array('147','Thủ kho Than',41));
$sth->execute(array('152','Tiếp phẩm',42));
$sth->execute(array('155','Nhân viên PV',43));
$sth->execute(array('156','Nhân viên bảo vệ',44));
$sth->execute(array('157','Trực y tế',45));
$sth->execute(array('158','Công nhân PV',46));
$sth->execute(array('160','Lái xe con',47));
$sth->execute(array('161','Lái xe cứu thương',48));
$sth->execute(array('162','Cấp dưỡng',49));
$sth->execute(array('164','Giặt sấy quần áo',50));
$sth->execute(array('165','Công nhân bốc xếp vật tư',51));
$sth->execute(array('167','S/c cơ điện lò',52));
$sth->execute(array('169','Công nhân VH Trạm bơm hầm lò',53));
$sth->execute(array('170','Công nhân VH Nén khí hầm lò',54));
$sth->execute(array('171','S/c điện thoại trong lò',55));
$sth->execute(array('172','Công nhân TGĐK',56));
$sth->execute(array('173','Quan trắc khí mỏ',57));
$sth->execute(array('174','Lái xe cẩu',58));
$sth->execute(array('175','Lái xe ca',59));
$sth->execute(array('176','Phụ xe ca',60));
$sth->execute(array('177','Sửa chữa ô tô',61));
$sth->execute(array('178','S/c cơ điện ngoài lò',62));
$sth->execute(array('179','S/c cơ điện ngoài lò',63));
$sth->execute(array('181','Nhà đèn',64));
$sth->execute(array('182','V/h máy nén khí ngoài lò',65));
$sth->execute(array('183','V/h nồi hơi',66));
$sth->execute(array('184','Công nhân trạm mạng',67));
$sth->execute(array('185','Bơm nước',68));
$sth->execute(array('186','V/h trạm biến áp',69));
$sth->execute(array('187','Công nhân KCS',70));
$sth->execute(array('188','Trạm quạt',71));
$sth->execute(array('189','Gia công cơ khí',72));
$sth->execute(array('190','Trạm cân',73));
$sth->execute(array('192','Thợ lò',74));
$sth->execute(array('193','Công nhân khoan hầm lò',75));
$sth->execute(array('195','V/h tầu điện',76));
$sth->execute(array('196','V/h băng tải',77));
$sth->execute(array('197','V/h tời trục',78));
$sth->execute(array('198','Lò trưởng',79));
$sth->execute(array('199','Lái máy gạt',80));
$sth->execute(array('201','Lái máy xúc thuỷ lực',81));
$sth->execute(array('202','Lái xe tải',82));
$sth->execute(array('203','Nổ mìn lộ thiên',83));
$sth->execute(array('204','Công nhân sàng tuyển',84));
$sth->execute(array('205','Công nhân Xây dựng',85));
$sth->execute(array('206','Lái máy xúc lật',86));
$sth->execute(array('207','Phụ máy xúc lật',87));
$sth->execute(array('208','Quan trắc khí mỏ hầm lò',88));
$sth->execute(array('209','Quan trắc khí mỏ ngoài lò',89));
$sth->execute(array('210','Văn thư',90));
$sth->execute(array('213','Công nhân Gác cửa lò',91));
$sth->execute(array('214','Công nhân V/h trạm 6 KV trong hầm lò',92));
$sth->execute(array('215','Công nhân lái xe ca',93));
$sth->execute(array('216','Công nhân phụ xe ca',94));
$sth->execute(array('217','Công nhân s/c ô tô',95));
$sth->execute(array('218','Công nhân GSAT',96));
$sth->execute(array('219','Công nhân bốc xếp Vật tư',97));
$sth->execute(array('220','Thủ kho Vật liệu nổ',98));
$sth->execute(array('221','Thủ kho Vật tư',99));
$sth->execute(array('222','Thủ kho Nhiên liệu',100));
$sth->execute(array('223','Thủ kho than',101));
$sth->execute(array('224','Thủ kho gỗ',102));
$sth->execute(array('225','Thủ kho Thực phẩm',103));
$sth->execute(array('226','Phụ kho Than',104));
$sth->execute(array('227','Phụ kho Vật tư',105));
$sth->execute(array('228','Thủ kho Văn phòng phẩm',106));
$sth->execute(array('229','Thủ kho Vật liệu xây dựng',107));
$sth->execute(array('230','Bảo vệ',108));
$sth->execute(array('231','Chuyên viên',109));

/*
//Report
$sth = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_report (title, ngaynghi, lydo, donvi, addtime, user) VALUES (?, ?, ?, ?, ?, ?)");
for($i=0;$i<365;$i++){
$d=mktime(0, 0, 0, date("m"), date("d")-$i, date("Y"));

}
*/
// Danh sach lao dong
//$sth = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_employee (code, name, pos, level, groups, dep, phucap, luongcb, status) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)");