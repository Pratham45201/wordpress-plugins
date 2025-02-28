<div id="wp_employee_crud_plugin">

    <!-- Add employee layout -->
    <h3>Add Employees</h3>

    <form action="javascript:void(0)" id="frm_add_employee" enctype="multipart/form-data">
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

        <p>
            <label for="profile">Profile Image</label>
            <input type="file" name="file" id="file">
        </p>

        <p>
            <button id="btn_save_data" type="submit">Submit</button>
        </p>

    </form>

    <!-- List employee layout -->
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
        <tbody>
            <tr>
                <td>1</td>
                <td>Pratham Patel</td>
                <td>pratham@example.com</td>
                <td>Wordpress Developer</td>
                <td>--</td>
                <td>
                    <button class="btn_edit_employee">Edit</button>
                    <button class="btn_delete_employee">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>