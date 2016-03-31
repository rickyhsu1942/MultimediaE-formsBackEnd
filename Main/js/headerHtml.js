// 同意條列
html_body = '<li><a  href="agreementUpdate.php"><span>同意條列</span></a></li>';
// 黑名單管理
html_body += '<li><a  href="blackListUpdateDelete.php"><span>黑名單管理</span></a></li>';
// 逾期未歸還之器材表單
html_body += '<li><a  href="expiredGeneralUpdateDelete.php"><span>逾期未歸還之器材表單</span><i class="icon2"></i></a>';
html_body += '<ul><li><a href="expiredGeneralUpdateDelete.php">一般借用表單</a></li>';
html_body += '<li><a href="expiredTeamUpdateDelete.php">展覽借用表單</a></li>';
html_body += '<li><a href="expiredDepartmentUpdateDelete.php">其他單位借用表單</a></li>';
html_body += '<li><a href="expiredRepairUpdateDelete.php">送修表單</a></li></ul></li>';
// 器材借用
html_body += '<li><a href="generalUpdateDelete.php"><span>器材借用單</span><i class="icon2"></i></a>';
html_body += '<ul><li><a href="generalUpdateDelete.php">一般借用表單</a></li>';
html_body += '<li><a href="teamUpdateDelete.php">展覽借用表單</a></li>';
html_body += '<li><a href="departmentUpdateDelete.php">其他單位借用表單</a></li>';
html_body += '<li><a href="repairUpdateDelete.php">送修表單</a></li></ul></li>';
// 器材型錄表單
html_body += '<li><a href="equipmentUpdateDelete.php"><span>器材型錄表單</span><i class="icon2"></i></a>';
html_body += '<ul><li><a href="equipmentSelect.php">財產編號器具</a>';
html_body += '<ul><li><a href="equipmentSelect.php">查詢</a></li>';
html_body += '<li><a href="equipmentUpdateDelete.php">修改/刪除</a></li>';
html_body += '<li><a href="equipmentInsert.php">新增</a></li></ul></li>';
html_body += '<li><a href="otherEquipmentSelect.php">無財產編號器具</a>';
html_body += '<ul><li><a href="otherEquipmentSelect.php">查詢</a></li>';
html_body += '<li><a href="otherEquipmentUpdateDelete.php">修改/刪除</a></li>';
html_body += '<li><a href="otherEquipmentInsert.php">新增</a></li></ul></li></ul></li>';
// 帳號管理
html_body += '<li class="last"><a href="accountUpdate.php"><span>帳號管理</span><i class="icon2"></i></a>';
html_body += '<ul><li><a href="accountUpdate.php">修改密碼</a></li>';
html_body += '<li><a href="accountInsert.php">新增帳號</a></li>';
html_body += '<li><a href="accountManage.php">管理帳號權限</a></li></ul></li>';

document.write(html_body);