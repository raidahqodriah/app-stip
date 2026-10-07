<?php

return [
    'request_status' => [
        'draft' => 'Draft',
        'submitted' => 'Pending Verification',
        'revision_requested' => 'Revision Requested',
        'verified' => 'Verified',
        'approved' => 'Approved',
        'in_use' => 'In Use / Ongoing',
        'completed' => 'Completed',
        'rejected' => 'Rejected',
        'cancelled' => 'Cancelled',
        'no_show' => 'No Show',
        'expired' => 'Expired',
    ],

    'circulation_status' => [
        'borrowed' => 'Borrowed',
        'returned' => 'Returned',
    ],

    'item_condition' => [
        'good' => 'Good Condition',
        'minor_damage' => 'Minor Damage',
        'major_damage' => 'Major Damage',
        'lost' => 'Lost',
    ],

    'bmn_item_status' => [
        'active' => 'Active',
        'returned' => 'Returned',
        'disposed' => 'Disposed',
    ],

    'bmn_movement_source' => [
        'submission' => 'New Submission',
        'return' => 'Return',
        'manual' => 'Manual / Internal Transfer',
    ],

    'material_type' => [
        'consumable' => 'Consumable Material',
        'equipment' => 'Equipment',
        'module' => 'Practice Module',
    ],

    'room_type' => [
        'lab' => 'Laboratory / Simulator',
        'classroom' => 'Classroom',
        'office' => 'Office',
        'warehouse' => 'Warehouse',
        'other' => 'Other',
    ],

    'subject_category' => [
        'teknika' => 'Marine Engineering',
        'nautika' => 'Nautical Studies',
        'kalk' => 'Port & Shipping Logistics (KALK)',
    ],

    'unit_type' => [
        'study_program' => 'Study Program',
        'work_unit' => 'Work Unit',
        'service_unit' => 'Service Unit',
    ],
];
