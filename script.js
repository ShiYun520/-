let data = []; // 全局数据变量
const rowsPerPage = 6; // 每页显示的行数
let currentPage = 1; // 当前页

// 从 API 获取数据
const fetchData = async () => {
    try {
        const response = await fetch("api.php"); // 请求 API
        const apiData = await response.json();
        data = apiData; // 将 API 返回的数据赋值给全局变量 data

        // 初始化页面
        loadTableData(currentPage);
        loadPagination();
    } catch (error) {
        console.error("获取数据失败：", error);
    }
};

// 加载数据到表格
const loadTableData = (page) => {
    const tableBody = document.getElementById("table-body");
    tableBody.innerHTML = ""; // 清空表格内容

    // 计算当前页的数据
    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const currentData = data.slice(start, end);

    // 插入数据行
    currentData.forEach((item) => {
        const row = `
            <tr>
                <td>${item.name}</td>
                <td>${item.traffic}</td>
                <td>${item.days}</td>
                <td>${item.uploadTime}</td>
                <td>
                    <button class="btn btn-info btn-sm" onclick="toggleDetails(${item.id})">详情</button>
                </td>
            </tr>
            <tr class="details-row" id="details-${item.id}">
                <td colspan="5">
                    <div class="details-content">
                        <div class="details-header">订阅详细信息</div>
                        <div class="details-item"><strong>订阅名称：</strong>${item.name}</div>
                        <div class="details-item"><strong>可用流量：</strong>${item.traffic} G</div>
                        <div class="details-item"><strong>上传时间：</strong>${item.days}</div>
                        <div class="details-item"><strong>剩余天数：</strong>${item.uploadTime}</div>
                        <div class="details-item details-link">
                            <strong>链接：</strong><a href="${item.link}" target="_blank">${item.link}</a>
                        </div>
                    </div>
                </td>
            </tr>
        `;
        tableBody.insertAdjacentHTML("beforeend", row);
    });
};

// 加载分页按钮
const loadPagination = () => {
    const pagination = document.getElementById("pagination");
    pagination.innerHTML = ""; // 清空分页按钮

    const totalPages = Math.ceil(data.length / rowsPerPage);

    // 上一页按钮
    const prevButton = `
        <li class="page-item ${currentPage === 1 ? "disabled" : ""}">
            <a class="page-link" href="#" onclick="changePage(${currentPage - 1})">&laquo;</a>
        </li>
    `;
    pagination.insertAdjacentHTML("beforeend", prevButton);

    // 页码按钮
    for (let i = 1; i <= totalPages; i++) {
        const pageButton = `
            <li class="page-item ${i === currentPage ? "active" : ""}">
                <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
            </li>
        `;
        pagination.insertAdjacentHTML("beforeend", pageButton);
    }

    // 下一页按钮
    const nextButton = `
        <li class="page-item ${currentPage === totalPages ? "disabled" : ""}">
            <a class="page-link" href="#" onclick="changePage(${currentPage + 1})">&raquo;</a>
        </li>
    `;
    pagination.insertAdjacentHTML("beforeend", nextButton);
};

// 切换页面
const changePage = (page) => {
    const totalPages = Math.ceil(data.length / rowsPerPage);
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    loadTableData(page);
    loadPagination();
};

// 切换详情显示/隐藏
const toggleDetails = (id) => {
    const detailsRow = document.getElementById(`details-${id}`);
    if (detailsRow.style.display === "none" || !detailsRow.style.display) {
        detailsRow.style.display = "table-row"; // 显示详情
    } else {
        detailsRow.style.display = "none"; // 隐藏详情
    }
};

// 初始化页面
fetchData();