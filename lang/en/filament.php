<?php

return [
    'brand' => [
        'admin' => 'SILT-STIP (Admin & Staff)',
        'student' => 'SILT-STIP (Cadet Portal)',
    ],

    'clusters' => [
        'lab' => [
            'name' => 'Lab & Simulator Services',
            'breadcrumb' => 'Lab / SPP',
        ],
        'bmn' => [
            'name' => 'State Assets & Housing',
            'breadcrumb' => 'State Assets & Housing',
        ],
        'library' => [
            'name' => 'Library',
            'breadcrumb' => 'Library',
        ],
        'master' => [
            'name' => 'Master & Settings',
            'breadcrumb' => 'Master Data',
        ],
    ],

    'resources' => [
        'audit_logs' => [
            'label' => 'Audit Log',
            'plural_label' => 'Audit Logs',
            'navigation_label' => 'Audit Logs',
        ],
        'blackout_dates' => [
            'label' => 'Blackout Date',
            'plural_label' => 'Blackout & Maintenance Dates',
            'navigation_label' => 'Blackout & Maintenance Dates',
        ],
        'bmn_items' => [
            'label' => 'BMN Asset',
            'plural_label' => 'BMN Assets',
            'navigation_label' => 'BMN Inventory Records',
        ],
        'bmn_returns' => [
            'label' => 'BMN Asset Return',
            'plural_label' => 'BMN Asset Returns',
            'navigation_label' => 'BMN Asset Returns',
        ],
        'bmn_submissions' => [
            'label' => 'BMN Submission',
            'plural_label' => 'BMN Submissions',
            'navigation_label' => 'New BMN Submissions',
        ],
        'bookings' => [
            'label' => 'Lab Booking',
            'plural_label' => 'Lab & Simulator Bookings',
            'navigation_label' => 'Lab & Simulator Bookings',
        ],
        'books' => [
            'label' => 'Library Book',
            'plural_label' => 'Library Book Catalog',
            'navigation_label' => 'Library Book Catalog',
        ],
        'circulations' => [
            'label' => 'Book Circulation',
            'plural_label' => 'Circulations & Fines',
            'navigation_label' => 'Circulations & Fines',
        ],
        'competences' => [
            'label' => 'IMO / STCW Competence',
            'plural_label' => 'IMO / STCW Competences',
            'navigation_label' => 'IMO / STCW Competences',
        ],
        'document_templates' => [
            'label' => 'Document Print Template',
            'plural_label' => 'Document Print Templates',
            'navigation_label' => 'Document Print Templates',
        ],
        'employees' => [
            'label' => 'Staff / Lecturer',
            'plural_label' => 'Staff & Lecturers Directory',
            'navigation_label' => 'Staff & Lecturers Directory',
        ],
        'imo_model_courses' => [
            'label' => 'IMO Model Course',
            'plural_label' => 'IMO Model Courses',
            'navigation_label' => 'IMO Model Courses',
        ],
        'materials' => [
            'label' => 'Practice Material / Tool',
            'plural_label' => 'Practice Materials & Tools',
            'navigation_label' => 'Practice Materials & Tools',
        ],
        'official_residences' => [
            'label' => 'Official Residence',
            'plural_label' => 'Official Residences',
            'navigation_label' => 'Official Residences',
        ],
        'residence_permits' => [
            'label' => 'Residence Permit (SIP)',
            'plural_label' => 'Residence Permits (SIP)',
            'navigation_label' => 'Housing Permits (SIP)',
        ],
        'rooms' => [
            'label' => 'Laboratory & Room',
            'plural_label' => 'Labs & Simulator Rooms',
            'navigation_label' => 'Labs & Simulator Rooms',
        ],
        'settings' => [
            'label' => 'System Setting',
            'plural_label' => 'System Settings',
            'navigation_label' => 'System Settings',
        ],
        'students' => [
            'label' => 'Cadet',
            'plural_label' => 'Cadets Directory',
            'navigation_label' => 'Cadets Directory',
        ],
        'subjects' => [
            'label' => 'Course / Subject',
            'plural_label' => 'Courses & Subjects',
            'navigation_label' => 'Courses & Subjects',
        ],
        'roles' => [
            'label' => 'User Role',
            'plural_label' => 'Roles & Permissions',
            'navigation_label' => 'Roles & Permissions',
        ],
        'permissions' => [
            'label' => 'Permission',
            'plural_label' => 'Permission Directory',
            'navigation_label' => 'Permission Directory',
        ],
        'units' => [
            'label' => 'Unit & Study Program',
            'plural_label' => 'Units & Study Programs',
            'navigation_label' => 'Units & Study Programs',
        ],

        // Student panel specific
        'student_bookings' => [
            'label' => 'Independent Lab Booking',
            'plural_label' => 'Independent Lab Bookings',
            'navigation_label' => 'Independent Lab Booking',
        ],
        'student_books' => [
            'label' => 'Book Catalog',
            'plural_label' => 'Library Book Catalog',
            'navigation_label' => 'Library Catalog',
        ],
        'student_circulations' => [
            'label' => 'Book Loan',
            'plural_label' => 'My Borrowed Books',
            'navigation_label' => 'My Borrowed Books',
        ],
    ],

    'widgets' => [
        'admin' => [
            'pending_bookings' => 'Lab Bookings Pending Verification',
            'approved_bookings_desc' => ':count sessions approved',
            'pending_bmn' => 'New BMN Submissions',
            'total_bmn_desc' => 'Total :count BMN units registered',
            'active_loans' => 'Books Currently Borrowed',
            'active_loans_desc' => 'Active library circulation records',
            'pending_sip' => 'Housing Permit Applications',
            'pending_sip_desc' => 'Awaiting review / approval',
        ],
        'student' => [
            'my_bookings' => 'My Independent Booking Requests',
            'my_bookings_desc' => 'Lab / simulator practice session history',
            'active_loans' => 'Books Currently Borrowed',
            'active_loans_desc' => 'Maximum quota 3 books',
            'unpaid_fines' => 'Outstanding Overdue Fines',
            'fines_desc_active' => 'Please settle at the library desk',
            'fines_desc_none' => 'No outstanding fines',
        ],
        'filters' => [
            'this_month' => 'This Month',
            '3_months' => 'Past 3 Months',
            'this_year' => 'This Year',
        ],
        'titles' => [
            'lab_utilization_trend' => 'Lab & Simulator Utilization Trend',
            'lab_usage_by_category' => 'Lab Usage by Category',
            'top_subjects_lab_usage' => 'Top Subjects by Lab Usage',
            'imo_competence_coverage' => 'IMO Model Course Coverage',
            'today_lab_schedule' => "Today's Lab & Simulator Schedule",
            'pending_lab_bookings' => 'Pending Lab Bookings Queue',
            'low_stock_materials' => 'Low Stock Materials Warning',
            'bmn_condition_distribution' => 'BMN Asset Condition Distribution',
            'bmn_per_unit' => 'BMN Assets per Work Unit',
            'pending_bmn_submissions' => 'Pending BMN Submissions',
            'damaged_bmn_items' => 'Damaged BMN Assets Follow-up',
            'bmn_pending_decree_documents' => 'BMN Assets Pending Decree Document',
            'residence_occupancy' => 'Official Residence Occupancy Status',
            'residence_permit_status' => 'Housing Permit Application Status',
            'pending_residence_permits' => 'Pending Housing Permit Applications',
            'expiring_residence_permits' => 'Expiring Housing Permits',
            'library_circulation_trend' => 'Circulation Trends (Loans vs Returns)',
            'top_borrowed_books' => 'Top 5 Most Borrowed Books',
            'books_by_category' => 'Book Collections by Discipline',
            'overdue_circulations' => 'Overdue Book Loans & Fines',
            'today_library_circulations' => "Today's Library Circulation Activity",
            'low_stock_books' => 'Books with Low / Zero Available Stock',
            'student_monthly_study_activity' => 'Independent Practice & Library Activity',
            'student_upcoming_lab_sessions' => 'My Enrolled Lab Practice Sessions',
            'student_active_loans' => 'My Borrowed Books & Due Dates',
        ],
    ],

    'language_switcher' => [
        'switch_language' => 'Change Language',
        'current_language' => 'Active Language',
        'id' => 'Bahasa Indonesia',
        'en' => 'English (US)',
    ],
];
