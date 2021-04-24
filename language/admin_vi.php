<?php

/**
 * @Project Module Attendance
 * @Author ChungNT (chung_vuitinh@yahoo.com)
 * @Createdate 25-2-2016
 */

if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$lang_translator['author'] = 'CHUNGNT (chung_vuitinh@yahoo.com)';
$lang_translator['createdate'] = '24/02/2016';
$lang_translator['langtype'] = 'lang_module';

$lang_module['manager'] = 'Quản lý';
$lang_module['attendance'] = 'Chấm công';
$lang_module['employee'] = 'Lao động';
$lang_module['employee_list'] = 'Danh sách Lao động';
$lang_module['add_employee'] = 'Thêm lao động';
$lang_module['edit_employee'] = 'Sửa lao động';
$lang_module['emp_code'] = 'Danh bộ';
$lang_module['emp_name'] = 'Họ và Tên';
$lang_module['emp_luongcb'] = 'Lương CB';
$lang_module['emp_phucap'] = 'Phụ cấp';
$lang_module['status'] = 'Chấm dứt HĐ';

$lang_module['department'] = 'Đơn vị';
$lang_module['department_list'] = 'Danh sách Đơn vị';
$lang_module['add_dep'] = 'Thêm đơn vị';
$lang_module['edit_dep'] = 'Sửa đơn vị';
$lang_module['dep_code'] = 'Mã đơn vị';
$lang_module['dep_name'] = 'Tên đơn vị';

$lang_module['catalogue'] = 'Ký hiệu Công';
$lang_module['catalogue_list'] = 'Danh sách Ký hiệu công';
$lang_module['add_cat'] = 'Thêm công';
$lang_module['edit_cat'] = 'Sửa công';
$lang_module['cat_code'] = 'Mã công';
$lang_module['cat_name'] = 'Tên công';
$lang_module['cat_sub'] = 'Thuộc nhóm';

$lang_module['topic'] = 'Nhóm ký hiệu công';
$lang_module['topic_list'] = 'Danh sách nhóm Ký hiệu công';
$lang_module['add_topic'] = 'Thêm nhóm';
$lang_module['edit_topic'] = 'Sửa nhóm';
$lang_module['topic_name'] = 'Tên nhóm';
$lang_module['btn_addtop'] = 'Thêm mới';

$lang_module['report'] = 'Xuất báo cáo';
$lang_module['report01'] = 'BC01: Báo cáo công điểm';
$lang_module['report02'] = 'BC02: Tổng hợp lao động theo đơn vị';
$lang_module['report03'] = 'BC03: Tổng hợp lao động theo chức vụ';
$lang_module['report04'] = 'BC04: Tổng hợp xuất ăn';
$lang_module['report05'] = 'BC05: Tổng hợp công điểm sản phẩm';
$lang_module['report06'] = 'BC06: Bảng kê chứng từ thanh toán';
$lang_module['report_list'] = 'Danh sách Báo cáo';
$lang_module['add_report'] = 'Thêm báo cáo';
$lang_module['edit_report'] = 'Sửa báo cáo';
$lang_module['date'] = 'Ngày công';
$lang_module['score'] = 'Điểm';
$lang_module['addtime'] = 'Ngày nhập';
$lang_module['edittime'] = 'Ngày sửa';
$lang_module['user'] = 'Tài khoản';

$lang_module['position'] = 'Chức vụ';
$lang_module['position_list'] = 'Danh sách Chức vụ';
$lang_module['group_list'] = 'Danh sách Tổ';
$lang_module['group'] = 'Tổ';
$lang_module['group_code'] = 'Mã tổ';
$lang_module['group_name'] = 'Tên tổ';
$lang_module['level'] = 'Bậc lương';
$lang_module['level_list'] = 'Danh sách bậc lương';
$lang_module['level_name'] = 'Tên bậc lương';
$lang_module['rate'] = 'Phân loại';
$lang_module['rate_list'] = 'Danh sách phân loại';
$lang_module['rate_name'] = 'Tên phân loại';
$lang_module['meal'] = 'Mức ăn';
$lang_module['meal_list'] = 'Danh sách mức ăn';
$lang_module['meal_name'] = 'Mức tiền';

$lang_module['import'] = 'Nhập từ Excel';
$lang_module['import_successful'] = 'Nhập dữ liệu thành công: ';
$lang_module['upload'] = 'Tệp dữ liệu excel';
$lang_module['dataname'] = 'Nhập dữ liệu';
$lang_module['is_xls'] = 'Chỉ nhập dữ liệu từ Excel 97-2003 (.xls)';
$lang_module['is_dated'] = ' đã chấm công ngày ';
$lang_module['attend_date'] = 'Xem dữ liệu chấm công ngày';
$lang_module['invalid'] = ' không đúng ký hiệu công';
$lang_module['line'] = 'Dòng ';

$lang_module['btn_add'] = 'Thêm mới';
$lang_module['btn_save'] = 'Lưu thay đổi';
$lang_module['error_empty'] = 'Bạn phải nhập đủ thông tin được yêu cầu (*).';
$lang_module['error_save'] = 'Lỗi trong quá trình lưu dữ liệu';
$lang_module['option'] = 'Chọn';
$lang_module['del_no_items'] = "Chưa mục nào được chọn!";
$lang_module['del_confirm'] = "Dữ liệu bị xóa sẽ không thể khôi phục!";
$lang_module['filter_enterkey'] = "Nhập dữ liệu cần tìm";
$lang_module['del_ok'] = 'Đã xóa xong';
$lang_module['existed'] = 'đã có trong cơ sở dữ liệu!';
$lang_module['not_existed'] = 'không có trong cơ sở dữ liệu!';
$lang_module['not_in'] = ' không ở ';
$lang_module['days_in_month'] = 'Ngày làm việc trong tháng';
$lang_module['total_cat'] = 'Tổng cộng chi tiết số công nghỉ việc';
$lang_module['total'] = 'Tổng:';
$lang_module['export_type'] = 'Loại báo cáo';
$lang_module['export_detail'] = 'Báo cáo chi tiết lao động';
$lang_module['export_status'] = 'Báo cáo tình hình lao động';
$lang_module['export_import_QLNS'] = 'Import phần mềm QLNS';
$lang_module['export_xls'] = 'Xuất ra excel';
$lang_module['from'] = 'Từ ngày';
$lang_module['to'] = 'Đến';
$lang_module['info'] = 'Thông tin';
$lang_module['chenhlech'] = 'Chênh lệch';
$lang_module['vld'] = 'Vô lý do';
$lang_module['bdn'] = 'Báo đảo nhiệm';
$lang_module['ktotal'] = 'Tổng công SP';
$lang_module['ratio_working'] = 'Tỉ lệ (%) đi làm theo';
$lang_module['vacation'] = 'Công nghỉ';
$lang_module['list'] = 'Danh sách';
$lang_module['no_permission'] = 'Bạn không được cấp quyền. Hãy liên hệ với Quản trị hệ thống!';

// plugin import
$lang_module['download'] = 'Tải file mẫu';
$lang_module['import'] = 'Nhập từ Excel';
$lang_module['import_file'] = 'File';
$lang_module['import_start'] = 'Bắt đầu';
$lang_module['adduser_email1_note'] = 'Gửi email thông báo tài khoản đã được tạo, thông tin bao gồm tên đăng nhập và mật khẩu. Quá trình gửi mail có thể làm chậm tiến trình import, bạn cần cân nhắc khi sử dụng tùy chọn này';
$lang_module['view_mail_note'] = 'Hiển thị email thành viên tại trang xem thông tin';
$lang_module['step1'] = 'Dữ liệu';
$lang_module['step1_a'] = 'Tải về và nhập thông tin theo mẫu';
$lang_module['step1_b'] = 'Tải lên file chứa thông tin thành viên';
$lang_module['step1_c'] = 'Kiểm tra dữ liệu';
$lang_module['step1_c_start'] = 'Bắt đầu kiểm tra';
$lang_module['step2'] = 'Cấu hình';
$lang_module['step3'] = 'Thực hiện';
$lang_module['next'] = 'Tiếp theo';
$lang_module['back'] = 'Quay lại';
$lang_module['guide'] = 'Hướng dẫn';
$lang_module['required'] = 'Bắt buộc';
$lang_module['required_0'] = 'Không';
$lang_module['required_1'] = 'Có';
$lang_module['note'] = 'Ghi chú';
$lang_module['comment_username'] = 'Tên đăng nhập cần tuân theo cấu hình module';
$lang_module['comment_password'] = 'Mật khẩu cần tuân theo cấu hình module';
$lang_module['comment_sig'] = '<strong>%d</strong> đến <strong>%d</strong> ký tự';
$lang_module['start'] = 'Bắt đầu';
$lang_module['reading'] = 'Đang tải file...';
$lang_module['users_list'] = 'Danh sách tài khoản';
$lang_module['checking'] = 'Đang kiểm tra tài khoản <strong>%s</strong>...';
$lang_module['check_success'] = 'Kiểm tra hoàn tất!';
$lang_module['check_success_cofirm'] = 'Quá trình kiểm tra không phát hiện lỗi. Bạn có muốn tiếp tục ngay bây giờ?';
$lang_module['check_status'] = 'Trạng thái';
$lang_module['check_status_ok'] = 'Hợp lệ';
$lang_module['check_status_error'] = 'Lỗi';
$lang_module['error_count_1'] = 'tài khoản bị lỗi. Vui lòng <strong>kiểm tra và hiệu chỉnh</strong> thông tin dựa trên chi tiết lỗi, <strong>chọn file tải lên</strong> và thực hiện <strong>Bắt đầu kiểm tra</strong> lại!';
$lang_module['import_success'] = 'Thành công';
$lang_module['import_wating'] = 'Đang tạo tài khoản...';
$lang_module['import_wating_success'] = 'Tiến trình tạo tải khoản đã hoàn tất!';
$lang_module['import_wating_confirm'] = 'Tiến trình tạo tải khoản đã hoàn tất. Bạn có muốn kiểm tra danh sách tài khoản ngay bây giờ?';

$lang_module['error_required_file'] = 'Vui lòng chọn file dữ liệu!';
$lang_module['error_file_struct'] = 'Cấu trúc dữ liệu mẫu đã cũ, vui lòng tải file mới!';
$lang_module['error_required_data'] = 'File dữ liệu không chứa thông tin thành viên!';
$lang_module['error_deny_name'] = 'Rất tiếc, tài khoản %s đã bị cấm sử dụng để đăng ký tài khoản mới';
$lang_module['error_username_exist'] = 'Tài khoản đã tồn tại';
$lang_module['error_email_exist'] = 'Email đã tồn tại';
$lang_module['phpexcel_not_exists_title'] = 'Không tìm thấy thư viện yêu cầu';
$lang_module['phpexcel_not_exists_content'] = 'Bạn cần cài đặt thư viên PHPExcel để sử dụng tính năng này';