<div id="wp_employee_crud_plugin">

    <!-- Add employee layout -->
    <div class="form-container add_employee_form hide_element">
        <h3>Add Employees</h3>

        <form action="javascript:void(0)" id="frm_add_employee" enctype="multipart/form-data">

            <input type="hidden" name="action" value="wce_add_employee">

            <p>
                <label for="name">Name</label>
                <input required type="text" name="name" placeholder="Employee name" id="name">
            </p>

            <p>
                <label for="email">Email</label>
                <input required type="email" name="email" placeholder="Employee email" id="email">
            </p>

            <p>
                <label for="designation">Designation</label>
                <select name="designation" id="designation">
                    <option value="">-- Choose Designation --</option>
                    <option value="php">PHP Developer</option>
                    <option value="full">Full Stack developer</option>
                    <option value="wordpress">Wordpress developer</option>
                </select>
            </p>

            <div class="form-group">
                <label for="profile_image">Profile Image</label>
                <input type="file" name="profile_image" id="file">
            </div>

            <p>
                <button id="btn_save_data" type="submit">Submit</button>
            </p>

        </form>
    </div>

    <!-- Edit employee layout -->
    <div class="form-container edit_employee_form hide_element">
        <h3>Edit Employee</h3>

        <form action="javascript:void(0)" id="frm_edit_employee" enctype="multipart/form-data">

            <input type="hidden" name="action" value="wce_edit_employee">
            <input type="hidden" name="e_id" id="e_id">

            <p>
                <label for="e_name">Name</label>
                <input required type="text" name="e_name" placeholder="Employee name" id="e_name">
            </p>

            <p>
                <label for="e_email">Email</label>
                <input required type="email" name="e_email" placeholder="Employee email" id="e_email">
            </p>

            <p>
                <label for="e_designation">Designation</label>
                <select name="e_designation" id="e_designation">
                    <option value="">-- Choose Designation --</option>
                    <option value="php">PHP Developer</option>
                    <option value="full">Full Stack developer</option>
                    <option value="wordpress">Wordpress developer</option>
                </select>
            </p>

            <div class="form-group">
                <label for="e_profile_image">Profile Image</label>
                <input type="file" name="e_profile_image" id="e_file">
            </div>

            <p>
                <button id="btn_update_data" type="submit">update</button>
            </p>

        </form>
    </div>

    <!-- List employee layout -->
    <div class="list-container">
        <button style="float:right;" id="btn_open_add_employee_form">Add Employee</button>
        <h3>List Employees</h3>
        <table>
            <thead>
                <th>#ID</th>
                <th>#Name</th>
                <th>#Email</th>
                <th>#Designation</th>
                <th>#Profile Image</th>
                <th>#Action</th>
            </thead>

            <!-- Table of existing employees, updated in scripts.js -->
            <tbody id="employees_data_tbody"></tbody>
        </table>
    </div>
</div>