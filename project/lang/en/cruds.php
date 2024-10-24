<?php

return [
    'userManagement' => [
        'title'          => 'User management',
        'title_singular' => 'User management',
    ],
    'status' =>
    [
        'title' => 'Status',
        'aktif' => 'Active',
        'tidak_aktif' => 'Inactive',
        'action' => 'Action',
    ],
    'permission' => [
        'title'          => 'Permissions',
        'title_singular' => 'Permission',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'title'              => 'Title',
            'title_helper'       => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
            'description'        => 'Description',
            'description_helper' => ' ',
        ],
    ],
    'country' => [
        'list' => 'List Countries',
        'countries' => 'Countries',
        'title_singular' => 'Country',
        'title' => 'Country',
        'kode' => 'Kode',
        'nama' => 'Name',
        'iso1' => 'ISO-1',
        'iso2' => 'ISO-2',
        'flag' => 'Flag',
    ],
    'provinsi' => [
        'list' => 'List Provincies',
        'provincies' => 'Provincies',
        'title_singular' => 'Province',
        'title' => 'Province',
        'kode' => 'Code',
        'nama' => 'Name',
        'iso1' => 'ISO-1',
        'iso2' => 'ISO-2',
        'flag' => 'Flag',
    ],
    'kabupaten' => [
        'list' => 'List Regencies',
        'kabupatens' => 'Regencies',
        'title_singular' => 'Regency',
        'title' => 'Regency',
        'kabupaten' => 'Regency',
        'kode' => 'Code',
        'nama' => 'Name',
        'aktif' => 'Active',
        'provinsi' => 'Province',
        'kota' => 'City',
    ],
    'kecamatan' => [
        'list' => 'List of Subdistrict',
        'nama' => 'Subdistrict Name',
        'title_singular' => 'Subdistrict',
        'title' => 'Subdistrict',
        'kode' => 'Code',
        'name' => 'Name',
        'aktif' => 'Active',
        'kota' => 'City',
        'kode_validation'   => 'Invalid input. Please follow patern xx.xx.xx . Only numbers and dots are allowed.',
        'kab_validation'    => 'Invalid input. Please Select Regency.',
        'nama_validation'   => 'Invalid input. Must be at least 3 characters and cannot start with a number.',
    ],
    'desa' => [
        'list'          => 'List Villages',
        'title'         => 'Villages',
        'form'          => [
            'id'        => 'ID',
            'nama'      => 'Village Name',
            'kode'      => 'Code',
            'kab'       => 'Regency Name',
            'kec'       => 'Subdistrict Name',
            'prov'      => 'Province Name',
            'aktif'     => 'Active',
        ],
        'validation'    => [
            'nama'      => 'Invalid input. Please Input a valid Village Name.',
            'req_nama'  => 'Please provide Village Name.',
            'min_nama'  => 'Village Name must be at least 3 character.',
            'min_kode'  => 'Village Code must be at exact 13 code.',
            'req_kode'  => 'Please provide Village Code.',
            'kode'      => 'Invalid input. Please Input a valid Village Code. Format xx.xx.xx.xxxx',
            'kec'       => 'Invalid input. Please Select a Subdistrict.',
            'kab'       => 'Invalid input. Please Select a Regency.',
        ],
    ],
    'dusun' => [
        'list'          => 'List Hamlet',
        'title'         => 'Hamlet',
        'form'          => [
            'id'        => 'ID',
            'nama'      => 'Hamlet Name',
            'kode'      => 'Code',
            'kab'       => 'Regency Name',
            'kec'       => 'Subdistrict Name',
            'kode_pos'  => 'Postal Code',
            'des'       => 'Village Name',
            'prov'      => 'Province Name',
            'aktif'     => 'Active',
        ],
        'validation'    => [
            'nama'      => 'Invalid input. Please Input a valid Hamlet Name.',
            'req_nama'  => 'Please provide Hamlet Name.',
            'min_nama'  => 'Hamlet Name must be at least 3 character.',
            'min_kode'  => 'Hamlet Code must be at exact 16 code.',
            'max_kode'  => 'Hamlet Code must be at exact 16 code.',
            'req_kode'  => 'Please provide Hamlet Code.',
            'kode'      => 'Invalid input. Please Input a valid Hamlet Code. Format xx.xx.xx.xxxx.xx',
            'prov'      => 'Please Select a Province First.',
            'kec'       => 'Invalid input. Please Select a Subdistrict.',
            'kab'       => 'Invalid input. Please Select a Regency.',
            'des'       => 'Invalid input. Please Select a Village.',
        ],
    ],
    'negara' => [
        'title'          => 'Negara',
        'title_singular' => 'Negara',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'nama'              => 'Nama',
            'nama_helper'       => ' ',
            'iso_1'             => 'ISO-1',
            'iso_1_helper'      => ' ',
            'iso_2'             => 'ISO-2',
            'iso_2_helper'      => ' ',
            'flag'              => 'Flag',
            'flag_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'role' => [
        'title'          => 'Roles',
        'title_singular' => 'Role',
        'list'           => 'Role List',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'nama'               => 'Role Name',
            'title_helper'       => ' ',
            'permissions'        => 'Permissions',
            'permissions_helper' => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
        ],
        'validation'             => [
            'nama'               => 'Role Name must be at least 3 characters.',
            'permission'         => 'Please select at least one permission.',
        ],
    ],
    'user' => [
        'title'                        => 'Users',
        'title_singular'               => 'User',
        'list'                         => 'Users List',
        'fields'                       => [
            'id'                       => 'ID',
            'id_helper'                => ' ',
            'name'                     => 'Name',
            'nama'                     => 'Name',
            'username'                 => 'Username',
            'name_helper'              => ' ',
            'email'                    => 'Email',
            'email_helper'             => ' ',
            'email_verified_at'        => 'Email verified at',
            'email_verified_at_helper' => ' ',
            'password'                 => 'Password',
            'password_helper'          => ' ',
            'jabatan'                  => 'Position',
            'roles'                    => 'Roles System',
            'role'                     => 'Role',
            'roles_helper'             => ' ',
            'remember_token'           => 'Remember Token',
            'remember_token_helper'    => ' ',
            'created_at'               => 'Created at',
            'created_at_helper'        => ' ',
            'updated_at'               => 'Updated at',
            'updated_at_helper'        => ' ',
            'deleted_at'               => 'Deleted at',
            'deleted_at_helper'        => ' ',
        ],
        'validation'          => [
            'nama'            => 'Name must be at least 5 characters.',
            'username'        => 'Username must be at least 5 characters.',
            'email'           => 'Invalid email format.',
            'user_unique'     => 'Username must be unique.',
            'taken'           => 'has been taken.',
            'email_unique'    => 'Email has already been taken.',
            'password'        => 'Password must be at least 8 characters.',
            'username_admin'  => 'Only administrator that allowed to change username',
        ],
        'profile'             => [
            'profile'         => 'Profile',
            'label'           => 'User Profile',
            'update'          => 'Update Profile',
            'nama'            => 'Name',
            'bio'             => 'Bio',
            'password'        => 'Password',
            'change_password' => 'Change Password',
            'repeat_password' => 'Repeat Password',
            'images'          => 'Change Images',
        ],
    ],
    'auditLog' => [
        'title'          => 'Audit Logs',
        'title_singular' => 'Audit Log',
        'fields'         => [
            'id'                  => 'ID',
            'id_helper'           => ' ',
            'description'         => 'Description',
            'description_helper'  => ' ',
            'subject_id'          => 'Subject ID',
            'subject_id_helper'   => ' ',
            'subject_type'        => 'Subject Type',
            'subject_type_helper' => ' ',
            'user_id'             => 'User ID',
            'user_id_helper'      => ' ',
            'properties'          => 'Properties',
            'properties_helper'   => ' ',
            'host'                => 'Host',
            'host_helper'         => ' ',
            'created_at'          => 'Created at',
            'created_at_helper'   => ' ',
            'updated_at'          => 'Updated at',
            'updated_at_helper'   => ' ',
        ],
    ],
    'form' => [
        'kode' => 'Kode',
        'nama' => 'Nama',
        'submit' => 'Kirim',
        'cancel' => 'Batalkan',
    ],
    'data' => [
        'data'      => 'Data',
        'added'     => 'Added Successfully',
        'updated'   => 'Updated Successfully',
        'deleted'   => 'Has Been Deleted'
    ],
    'jenisbantuan' => [
        'list' => 'Daftar Jenis Bantuan',
        'jenisbantuan' => 'Jenis Bantuan',
        'title_singular' => 'Jenis Bantuan',
        'title' => 'Jenis Bantuan',
        'kode' => 'Kode',
        'no' => 'No',
        'nama' => 'Nama',
    ],
    'kategoripendonor' => [
        'list' => 'Category Donatur List',
        'kategoripendonor' => 'Category Donatur',
        'title_singular' => 'Category Donatur',
        'title' => 'Category Donatur',
        'kode' => 'Kode',
        'no' => 'No',
        'nama' => 'Name',
    ],
    'mpendonor' => [
        'list' => 'Donatur List',
        'mpendonor' => 'Donatur',
        'title_singular' => 'Donatur',
        'title' => 'Donatur',
        'kode' => 'Kode',
        'no' => 'No',
        'nama' => 'Name',
        'phone' => 'Phone/WA',
        'email' => 'Email',
        'pic' => 'PIC',
    ],
    'kelompokmarjinal' => [
        'list' => 'List of Vulnerable Groups',
        'kelompokmarjinal' => 'Vulnerable Groups',
        'title_singular' => 'Vulnerable Groups',
        'title' => 'Vulnerable Groups',
        'kode' => 'Kode',
        'no' => 'No',
        'nama' => 'Name',
    ],
    'partner'               => [
        'title'             => 'Partners',
        'list'              => 'Partners List',
        'title_singular'    => 'Partner',
        'fields'            => [
            'id'            => 'ID',
            'nama'          => 'Name',
            'nama_partner'  => 'Partner Name',
            'ket'           => 'Information',
            'created_at'    => 'Created at',
            'updated_at'    => 'Updated at',
        ]
    ],
    'reinstra'               => [
        'title'             => 'Strategy Plan Targets',
        'list'              => 'Strategy Plan Target List',
        'title_singular'    => 'Reinstra Target',
        'fields'            => [
            'id'            => 'ID',
            'nama'          => 'Name',
            'nama_reinstra' => 'Strategy Plan Target Name',
            'created_at'    => 'Created at',
            'updated_at'    => 'Updated at',
            'deleted_at'    => 'Deleted at',
        ]
    ],
    'satuan'               => [
        'title'             => 'Units',
        'list'              => 'Unit List',
        'title_singular'    => 'Unit',
        'fields'            => [
            'id'            => 'ID',
            'nama'          => 'Name',
            'nama_satuan'   => 'Unit Name',
            'created_at'    => 'Created at',
            'updated_at'    => 'Updated at',
            'deleted_at'    => 'Deleted at',
        ]
    ],

    'mjabatan' => [
        'list' => 'List',
        'title_singular' => 'Position',
        'title' => 'Position',
        'nama' => 'Position',
        'status' => 'Status',
    ],

    'kaitan_sdg' => [
        'list' => 'List',
        'title_singular' => 'Sustainable Development Goals',
        'title' => 'Sustainable Development Goals',
        'nama' => 'Sustainable Development Goals',
        'status' => 'Status',
    ],

    'peran' => [
        'list' => 'List',
        'title_singular' => 'Responsibility',
        'title' => 'Responsibility',
        'nama' => 'Responsibility',
        'status' => 'Status',
    ],

    'program' => [
        'title_singular'                         => 'Program',
        'title'                                  => 'Program',
        'nama'                                   => 'Program Name',
        'kode'                                   => 'Code',
        'tanggalmulai'                           => 'Start Date',
        'tanggalselesai'                         => 'End Date',
        'totalnilai'                             => 'Total Value',
        'ekspektasipenerimamanfaat'              => 'Expected Beneficiaries',
        'ekspektasipenerimamanfaatwoman'         => 'Expected Women Beneficiaries',
        'ekspektasipenerimamanfaatman'           => 'Expected Men Beneficiaries',
        'ekspektasipenerimamanfaatgirl'          => 'Expected Girl Beneficiaries',
        'ekspektasipenerimamanfaatboy'           => 'Expected Boy Beneficiaries',
        'ekspektasipenerimamanfaattidaklangsung' => 'Indirect Beneficiaries',
        'deskripsiprojek'                        => 'Project Description',
        'analisamasalah'                         => 'Problem Analysis',
        'status'                                 => 'Status',
        'list'              => 'List Program',
        'info_dasar'        => 'Basic Information',
        'expektasi'         => 'Beneficiary Expectations',
        'ex_indirect'       => 'Expected Indirect Benefit Receipt',

        'reinstra'          => 'Target Reinstra',
        'list_reinstra'     => 'List Target Reinstra',
        'select_reinstra'   => 'Select Target Reinstra',
        'sdg'               => 'SDG Linkages',
        'list_sdg'          => 'SDG List',
        'select_sdg'        => 'Select SDG',
        'deskripsi'         => 'Program Description',
        'analisis'          => 'Program Problem Analysis',
        'files'             => 'Supporting Files',
        'upload'            => 'Upload Files',
        'pilih_file'        => 'Select Files',
        'ket_file'          => 'Input File Information',
        'edit_file'          => 'Privew will display the selected file and will upload it, the previous file will remain unless the delete button is clicked.',
        'form'              => [
            'id'            => 'ID',
            'title'         => 'Program Information',
            'list'          => 'Program List',
            'nama'          => 'Name',
            'kode'          => 'Code',
            'tgl_mulai'     => 'Start Date',
            'tgl_selesai'   => 'Finish Date',
            'total_nilai'   => 'Total Program Value (IDR)',
            'pria'          => 'Men',
            'wanita'        => 'Women',
            'laki'          => 'Boys',
            'perempuan'     => 'Daughters',
            'total'         => 'Total Beneficiary Expectations',
            'created_at'    => 'Created at',
            'updated_at'    => 'Updated at',
            'deleted_at'    => 'Deleted at',
        ],
        'lokasi'            => [
            'label'         => 'Location',
            'pro'           => 'Province',
            'sel_pro'       => 'Select Province',
            'kab'           => 'District',
            'sel_kab'       => 'Select District',
            'kec'           => 'Subdistrict',
            'sel_kec'       => 'Select Subdistrict',
            'des'           => 'Village',
            'sel_des'       => 'Select Village',
            'dus'           => 'Hamlet',
            'select_dus'    => 'Select Hamlet',
            'select'        => 'Select Program Location',
        ],
        'donor'             => [
            'label'         => 'Donor',
            'select'        => 'Select Donor',
            'nama'          => 'Donor Name',
            'email'         => 'Email',
            'ph'            => 'Phone Number',
            'val'           => 'Donation Value',
        ],
        'staff'             => [
            'label'         => 'Staff',
            'select'        => 'Select Staff',
            'peran'         => 'Peran',
            'sel_peran'     => 'Select Peran',
        ],
        'outcome'           => [
            'label'         => 'Outcome',
            'desc'          => 'Description',
            'indicator'     => 'Indicator',
            'target'        => 'Target',
        ],
        'partner'           => [
            'label'         => 'Partner',
            'select'        => 'Select Partner',
        ],
        'report'            => [
            'label'         => 'Report Schedule',
            'to'            => 'Report to',
            'mulai'         => 'Date Start',
            'selesai'       => 'Date to',
        ],
        'objective'         => [
            'label'         => 'Objective',
        ],
        'goals'             => [
            'label'         => 'Goals',
            'desc'          => 'Description',
            'indicator'     => 'Indicator',
            'target'        => 'Target',
        ],
        'marjinal'            => [
            'label'         => 'Marginalized Group',
            'list'          => 'List of Marginalized Group',
            'select'        => 'Select Marginalized Group',
        ],
    ]
];
