<?php

use App\UserGroup;
use Illuminate\Database\Seeder;

class AddNewUserGroupsAndDefaultPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'userGroup' => [
                    'code' => 'Registrar',
                    'name' => 'Registrar',
                    'description' => 'Registrar'
                ],
                'permissions' => [
                    ['permission_id' => '231'],
                    ['permission_id' => '232'],
                    ['permission_id' => '241'],
                    ['permission_id' => '242'],
                    ['permission_id' => '251'],
                    ['permission_id' => '252'],
                    ['permission_id' => '281'],
                    ['permission_id' => '242'],
                    ['permission_id' => '191'],
                    ['permission_id' => '192'],
                    ['permission_id' => '193'],
                    ['permission_id' => '201'],
                    ['permission_id' => '202'],
                    ['permission_id' => '203'],
                    ['permission_id' => '211'],
                    ['permission_id' => '212'],
                    ['permission_id' => '213'],
                    ['permission_id' => '221'],
                    ['permission_id' => '222'],
                    ['permission_id' => '223'],
                    ['permission_id' => '141'],
                    ['permission_id' => '142'],
                    ['permission_id' => '143'],
                    ['permission_id' => '144'],
                    ['permission_id' => '321'],
                    ['permission_id' => '322'],
                    ['permission_id' => '111'],
                    ['permission_id' => '112'],
                    ['permission_id' => '113'],
                    ['permission_id' => '371'],
                    ['permission_id' => '372'],
                    ['permission_id' => '373'],
                    ['permission_id' => '331'],
                    ['permission_id' => '332'],
                    ['permission_id' => '333'],
                    ['permission_id' => '352'],
                    ['permission_id' => '353'],
                    ['permission_id' => '364'],
                    ['permission_id' => '363']
                ]
            ],
            [
                'userGroup' => [
                    'code' => 'Finance',
                    'name' => 'Finance',
                    'description' => 'Finance'
                ],
                'permissions' => [
                    ['permission_id' => '261'],
                    ['permission_id' => '262'],
                    ['permission_id' => '161'],
                    ['permission_id' => '171'],
                    ['permission_id' => '172'],
                    ['permission_id' => '173'],
                    ['permission_id' => '181'],
                    ['permission_id' => '182'],
                    ['permission_id' => '183'],
                    ['permission_id' => '291'],
                    ['permission_id' => '292'],
                    ['permission_id' => '301'],
                    ['permission_id' => '302'],
                    ['permission_id' => '303'],
                    ['permission_id' => '304'],
                    ['permission_id' => '311'],
                    ['permission_id' => '312'],
                    ['permission_id' => '313'],
                    ['permission_id' => '141'],
                    ['permission_id' => '142'],
                    ['permission_id' => '143'],
                    ['permission_id' => '144'],
                    ['permission_id' => '111'],
                    ['permission_id' => '112'],
                    ['permission_id' => '113'],
                    ['permission_id' => '263'],
                    ['permission_id' => '264'],
                    ['permission_id' => '265'],
                    ['permission_id' => '269'],
                    ['permission_id' => '270'],
                    ['permission_id' => '271'],
                    ['permission_id' => '266'],
                    ['permission_id' => '267'],
                    ['permission_id' => '268'],
                    ['permission_id' => '351'],
                    ['permission_id' => '352'],
                    ['permission_id' => '361'],
                    ['permission_id' => '363'],
                ]
            ]
        ];
        foreach ($data as $item) {
            $userGroup = userGroup::create($item['userGroup']);
            $userGroup->permissions()->sync($item['permissions']);
        }
    }
}
