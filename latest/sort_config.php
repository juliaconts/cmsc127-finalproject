<?php
    $allowed = [
        'none'          => 'lastName ASC, firstName ASC',
        'alphabet-asc'  => 'lastName ASC, firstName ASC',
        'alphabet-desc' => 'lastName DESC, firstName ASC',
        'role'          => 'r.roleID ASC, lastName ASC, firstName ASC',
        'status'        => 'a.status ASC, lastName ASC, firstName ASC',
        'soa-reqs'      => '',
        'pay-reqs'      => ''
    ];
?>