jQuery(document).ready(function () {
  console.log("CRUD plugins of employees");

  jQuery("#frm_add_employee").validate();

  // Form submit
  jQuery("#frm_add_employee").on("submit", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    jQuery.ajax({
      url: wce_object.ajax_url,
      data: formData,
      method: "POST",
      dataType: "json",
      contentType: false,
      processData: false,
      success: function (response) {
        if (response.status) {
          alert(response.message);
          loadEmployeeData(); // reload data on new data submission
        }
      },
    });
  });

  // Load employees data
  loadEmployeeData();

  // Delete function
  jQuery(document).on("click", ".btn_delete_employee", function () {
    var employeeId = jQuery(this).data("id");

    if (confirm("Are you sure want to delete")) {
      // true
      jQuery.ajax({
        url: wce_object.ajax_url,
        data: {
          action: "wce_delete_employee",
          empId: employeeId,
        },
        method: "GET",
        dataType: "json",
        success: function (response) {
          if (response) {
            alert(response.message);
            setTimeout(function () {
              location.reload();
            }, 1500);
          }
        },
      });
    }
    // false
  });

  // Open add employee form
  jQuery(document).on("click", "#btn_open_add_employee_form", function () {
    jQuery(".add_employee_form").toggleClass("hide_element");
  });

  // Open edit employee form
  jQuery(document).on("click", ".btn_open_edit_employee_form", function () {
    jQuery(".edit_employee_form").toggleClass("hide_element");
    // Get existing data of an employee by employee ID
    var employeeId = jQuery(this).data("id");
    jQuery.ajax({
      url: wce_object.ajax_url,
      data: {
        action: "wce_get_employee_data",
        empId: employeeId,
      },
      method: "GET",
      dataType: "json",
      success: function (response) {
        console.log(response);
        jQuery("#e_name").val(response?.data?.name);
        jQuery("#e_email").val(response?.data?.email);
        jQuery("#e_designation").val(response?.data?.designation);
        jQuery("#e_id").val(response?.data?.id);
      },
    });
  });

  // Submit edit form
  jQuery(document).on("submit", "#frm_edit_employee", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    jQuery.ajax({
      url: wce_object.ajax_url,
      data: formData,
      method: "POST",
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (response) {
        console.log("Edit success");
        setTimeout(function () {
          alert(response?.message);
          location.reload();
        }, 1500);
      },
    });
  });
});

// Load all employees from DB table
function loadEmployeeData() {
  jQuery.ajax({
    url: wce_object.ajax_url,
    data: {
      action: "wce_load_employees_data",
    },
    method: "GET",
    dataType: "json",
    success: function (response) {
      var employeesDataHTML = "";
      jQuery.each(response.employees, function (index, employee) {
        let employeeProfileImage = "--";
        if (employee.profile_image) {
          employeeProfileImage = `<img src="${employee.profile_image}" height="80px" width="80px" />`;
        }

        employeesDataHTML += `
            <tr>
                <td>${employee.id}</td>
                <td>${employee.name}</td>
                <td>${employee.email}</td>
                <td>${employee.designation}</td>
                <td>${employeeProfileImage}</td>
                <td>
                    <button data-id="${employee.id}" class="btn_open_edit_employee_form">Edit</button>
                    <button data-id="${employee.id}" class="btn_delete_employee">Delete</button>
                </td>
            </tr>
        `;
      });

      // Bind data with table
      jQuery("#employees_data_tbody").html(employeesDataHTML);
    },
  });
}
