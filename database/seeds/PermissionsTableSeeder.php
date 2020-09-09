<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            ['id' => 101, 'name' => 'Add Department', 'description' => 'Enable feature for adding new Department.', 'permission_group_id' => 1],
            ['id' => 102, 'name' => 'Edit Department', 'description' => 'Enable feature for editing Department.', 'permission_group_id' => 1],
            ['id' => 103, 'name' => 'Delete Department', 'description' => 'Enable feature for deleting Department.', 'permission_group_id' => 1],
        ]);

        DB::table('permissions')->insert([
            ['id' => 111, 'name' => 'Add School Year', 'description' => 'Enable feature for adding new School Year.', 'permission_group_id' => 3],
            ['id' => 112, 'name' => 'Edit School Year', 'description' => 'Enable feature for editing School Year.', 'permission_group_id' => 3],
            ['id' => 113, 'name' => 'Delete School Year', 'description' => 'Enable feature for deleting School Year.', 'permission_group_id' => 3],
        ]);

        DB::table('permissions')->insert([
            ['id' => 121, 'name' => 'Add School Category', 'description' => 'Enable feature for adding new School Category.', 'permission_group_id' => 4],
            ['id' => 122, 'name' => 'Edit School Category', 'description' => 'Enable feature for editing School Category.', 'permission_group_id' => 4],
            ['id' => 123, 'name' => 'Delete School Category', 'description' => 'Enable feature for deleting School Category.', 'permission_group_id' => 4],
        ]);

        DB::table('permissions')->insert([
            ['id' => 131, 'name' => 'Add User Group', 'description' => 'Enable feature for adding new User Group.', 'permission_group_id' => 5],
            ['id' => 132, 'name' => 'Edit User Group', 'description' => 'Enable feature for editing User Group.', 'permission_group_id' => 5],
            ['id' => 133, 'name' => 'Delete User Group', 'description' => 'Enable feature for deleting User Group.', 'permission_group_id' => 5],
        ]);

        DB::table('permissions')->insert([
            ['id' => 141, 'name' => 'Add Student', 'description' => 'Enable feature for adding new Student.', 'permission_group_id' => 6],
            ['id' => 142, 'name' => 'Edit Student', 'description' => 'Enable feature for editing Student.', 'permission_group_id' => 6],
            ['id' => 143, 'name' => 'Update Student Account', 'description' => 'Enable feature for updating Student Account.', 'permission_group_id' => 6],
            ['id' => 144, 'name' => 'Delete Student', 'description' => 'Enable feature for deleting Student.', 'permission_group_id' => 6],
        ]);

        DB::table('permissions')->insert([
            ['id' => 151, 'name' => 'Add Personnel', 'description' => 'Enable feature for adding new Personnel.', 'permission_group_id' => 7],
            ['id' => 152, 'name' => 'Edit Personnel', 'description' => 'Enable feature for editing Personnel.', 'permission_group_id' => 7],
            ['id' => 153, 'name' => 'Update Personnel Account', 'description' => 'Enable feature for updating Personnel Account.', 'permission_group_id' => 7],
            ['id' => 154, 'name' => 'Delete Personnel', 'description' => 'Enable feature for deleting Personnel.', 'permission_group_id' => 7],
        ]);

        DB::table('permissions')->insert([
            ['id' => 161, 'name' => 'Update Rate Sheet', 'description' => 'Enable feature for updating Rate Sheet.', 'permission_group_id' => 8]
        ]);

        DB::table('permissions')->insert([
            ['id' => 171, 'name' => 'Add Fee Category', 'description' => 'Enable feature for adding new Fee Category.', 'permission_group_id' => 9],
            ['id' => 172, 'name' => 'Edit Fee Category', 'description' => 'Enable feature for editing Fee Category.', 'permission_group_id' => 9],
            ['id' => 173, 'name' => 'Delete Fee Category', 'description' => 'Enable feature for deleting Fee Category.', 'permission_group_id' => 9],
        ]);

        DB::table('permissions')->insert([
            ['id' => 181, 'name' => 'Add School Fee', 'description' => 'Enable feature for adding new School Fee.', 'permission_group_id' => 10],
            ['id' => 182, 'name' => 'Edit School Fee', 'description' => 'Enable feature for editing School Fee.', 'permission_group_id' => 10],
            ['id' => 183, 'name' => 'Delete School Fee', 'description' => 'Enable feature for deleting School Fee.', 'permission_group_id' => 10],
        ]);

        DB::table('permissions')->insert([
            ['id' => 191, 'name' => 'Add Curriculum', 'description' => 'Enable feature for adding new Curriculum.', 'permission_group_id' => 11],
            ['id' => 192, 'name' => 'Edit Curriculum', 'description' => 'Enable feature for editing Curriculum.', 'permission_group_id' => 11],
            ['id' => 193, 'name' => 'Delete Curriculum', 'description' => 'Enable feature for deleting Curriculum.', 'permission_group_id' => 11],
        ]);

        DB::table('permissions')->insert([
            ['id' => 201, 'name' => 'Add Section & Schedule', 'description' => 'Enable feature for adding new Section & Schedule.', 'permission_group_id' => 12],
            ['id' => 202, 'name' => 'Edit Section & Schedule', 'description' => 'Enable feature for editing Section & Schedule.', 'permission_group_id' => 12],
            ['id' => 203, 'name' => 'Delete Section & Schedule', 'description' => 'Enable feature for deleting Section & Schedule.', 'permission_group_id' => 12],
        ]);

        DB::table('permissions')->insert([
            ['id' => 211, 'name' => 'Add Subject', 'description' => 'Enable feature for adding new Subject.', 'permission_group_id' => 13],
            ['id' => 212, 'name' => 'Edit Subject', 'description' => 'Enable feature for editing Subject.', 'permission_group_id' => 13],
            ['id' => 213, 'name' => 'Delete Subject', 'description' => 'Enable feature for deleting Subject.', 'permission_group_id' => 13],
        ]);

        DB::table('permissions')->insert([
            ['id' => 221, 'name' => 'Add Course', 'description' => 'Enable feature for adding new Course.', 'permission_group_id' => 14],
            ['id' => 222, 'name' => 'Edit Course', 'description' => 'Enable feature for editing Course.', 'permission_group_id' => 14],
            ['id' => 223, 'name' => 'Delete Course', 'description' => 'Enable feature for deleting Course.', 'permission_group_id' => 14],
        ]);

        DB::table('permissions')->insert([
            ['id' => 231, 'name' => 'Evaluation & Admission Approval', 'description' => 'Enable feature for approving Evaluation & Admission.', 'permission_group_id' => 15],
            ['id' => 232, 'name' => 'Evaluation & Admission Disapproval', 'description' => 'Enable feature for disapproving Evaluation & Admission.', 'permission_group_id' => 15],
        ]);

        DB::table('permissions')->insert([
            ['id' => 241, 'name' => 'Student Subject Approval', 'description' => 'Enable feature for approving Student Subject.', 'permission_group_id' => 16],
            ['id' => 242, 'name' => 'Student Subject Disapproval', 'description' => 'Enable feature for disapproving Student Subject.', 'permission_group_id' => 16],
        ]);

        DB::table('permissions')->insert([
            ['id' => 251, 'name' => 'Student Fee Approval', 'description' => 'Enable feature for approving Student Fee.', 'permission_group_id' => 17],
            ['id' => 252, 'name' => 'Student Fee Disapproval', 'description' => 'Enable feature for disapproving Student Fee.', 'permission_group_id' => 17],
        ]);

        DB::table('permissions')->insert([
            ['id' => 261, 'name' => 'Student Payment Approval', 'description' => 'Enable feature for approving Student Payment.', 'permission_group_id' => 18],
            ['id' => 262, 'name' => 'Student Payment Disapproval', 'description' => 'Enable feature for disapproving Student Payment.', 'permission_group_id' => 18],
        ]);
    }
}
