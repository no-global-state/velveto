<?php

use Site\Service\UserService;

// Non main-admin
$nonAdmin = [UserService::USER_ROLE_GUEST, UserService::USER_ROLE_USER, UserService::USER_ROLE_TRANSLATOR];
$regular = [UserService::USER_ROLE_GUEST, UserService::USER_ROLE_USER]; // Translator and admin are allowed

return [

    // Booking
    '/crm/all-bookings/(:var)' => [
        'controller' => 'Booking@allAction',
        'disallow' => $nonAdmin
    ],
    
    '/crm/booking/reserve' => [
        'controller' => 'Booking@reserveAction'
    ],

    '/crm/booking/update-status' => [
        'controller' => 'Booking@updateStatusAction'
    ],
    
    '/crm/booking/(:var)' => [
        'controller' => 'Booking@indexAction'
    ],
    
    '/crm/booking/details/(:var)' => [
        'controller' => 'Booking@detailsAction'
    ],
    
    '/crm/booking/delete/(:var)' => [
        'controller' => 'Booking@deleteAction'
    ],

    // Meals
    '/crm/meals' => [
        'controller' => 'Meals@indexAction',
        'disallow' => $regular
    ],
    
    '/crm/meals/add' => [
        'controller' => 'Meals@addAction',
        'disallow' => $nonAdmin
    ],

    '/crm/meals/edit/(:var)' => [
        'controller' => 'Meals@editAction',
        'disallow' => $regular
    ],
    
    '/crm/meals/delete/(:var)' => [
        'controller' => 'Meals@deleteAction',
        'disallow' => $nonAdmin
    ],
    
    '/crm/meals/save' => [
        'controller' => 'Meals@saveAction',
        'disallow' => $regular
    ],
    
    // Dictionary
    '/crm/dictionary' => [
        'controller' => 'Dictionary@indexAction',
        'disallow' => $regular
    ],
    
    '/crm/dictionary/add' => [
        'controller' => 'Dictionary@addAction',
        'disallow' => $nonAdmin
    ],

    '/crm/dictionary/edit/(:var)' => [
        'controller' => 'Dictionary@editAction',
        'disallow' => $regular
    ],

    '/crm/dictionary/delete/(:var)' => [
        'controller' => 'Dictionary@deleteAction',
        'disallow' => $nonAdmin
    ],

    '/crm/dictionary/save' => [
        'controller' => 'Dictionary@saveAction',
        'disallow' => $regular
    ],

    // District
    '/crm/district' => [
        'controller' => 'District@indexAction',
        'disallow' => $regular
    ],

    '/crm/district/edit/(:var)' => [
        'controller' => 'District@editAction',
        'disallow' => $regular
    ],

    '/crm/district/delete/(:var)' => [
        'controller' => 'District@deleteAction',
        'disallow' => $nonAdmin
    ],

    '/crm/district/add' => [
        'controller' => 'District@addAction',
        'disallow' => $nonAdmin
    ],

    '/crm/district/save' => [
        'controller' => 'District@saveAction',
        'disallow' => $regular
    ],

    // Room category
    '/crm/room-category' => [
        'controller' => 'RoomCategory@indexAction',
        'disallow' => $regular
    ],

    '/crm/room-category/add' => [
        'controller' => 'RoomCategory@addAction',
        'disallow' => $nonAdmin
    ],

    '/crm/room-category/save' => [
        'controller' => 'RoomCategory@saveAction',
        'disallow' => $regular
    ],

    '/crm/room-category/edit/(:var)' => [
        'controller' => 'RoomCategory@editAction',
        'disallow' => $regular
    ],
    
    '/crm/room-category/delete/(:var)' => [
        'controller' => 'RoomCategory@deleteAction',
        'disallow' => $nonAdmin
    ],

    // Hotel switch
    '/crm/hotel-switch/(:var)' => [
        'controller' => 'Crm@hotelSwitchAction',
        'disallow' => [UserService::USER_ROLE_GUEST, UserService::USER_ROLE_USER]
    ],
    
    // User settings
    '/user/change-password' => [
        'controller' => 'Settings@changePasswordAction'
    ],

    // Hotel types
    '/crm/hotel-type' => [
        'controller' => 'HotelType@indexAction'
    ],

    '/crm/hotel-type/add' => [
        'controller' => 'HotelType@addAction'
    ],

    '/crm/hotel-type/edit/(:var)' => [
        'controller' => 'HotelType@editAction'
    ],

    '/crm/hotel-type/delete/(:var)' => [
        'controller' => 'HotelType@deleteAction'
    ],

    '/crm/hotel-type/save' => [
        'controller' => 'HotelType@saveAction'
    ],

    // Wizard
    '/crm/wizard' => [
        'controller' => 'Wizard@indexAction'
    ],

    // Payment system field
    '/crm/payment-system/fields/all/(:var)' => [
        'controller' => 'PaymentField@indexAction'
    ],

    '/crm/payment-system/fields/edit/(:var)' => [
        'controller' => 'PaymentField@editAction'
    ],

    '/crm/payment-system/fields/delete/(:var)' => [
        'controller' => 'PaymentField@deleteAction'
    ],

    '/crm/payment-system/fields/save' => [
        'controller' => 'PaymentField@saveAction'
    ],

    // Payment system
    '/crm/payment-system' => [
        'controller' => 'PaymentSystem@indexAction'
    ],

    '/crm/payment-system/save' => [
        'controller' => 'PaymentSystem@saveAction'
    ],

    '/crm/payment-system/delete/(:var)' => [
        'controller' => 'PaymentSystem@deleteAction'
    ],

    '/crm/payment-system/edit/(:var)' => [
        'controller' => 'PaymentSystem@editAction'
    ],
    
    // Discounts
    '/crm/discount' => [
        'controller' => 'Discount@indexAction'
    ],
    
    '/crm/discount/save' => [
        'controller' => 'Discount@saveAction',
        'disallow' => [UserService::USER_ROLE_GUEST]
    ],

    '/crm/discount/delete/(:var)' => [
        'controller' => 'Discount@deleteAction',
        'disallow' => [UserService::USER_ROLE_GUEST]
    ],
    
    '/crm/discount/edit/(:var)' => [
        'controller' => 'Discount@editAction'
    ],

    // Property
    '/crm/property/(:var)' => [
        'controller' => 'Property@indexAction',
        'disallow' => $regular
    ],
    
    '/crm/property/do/tweak' => [
        'controller' => 'Property@tweakAction',
        'disallow' => $regular
    ],

    // Price groups
    '/crm/price-groups' => [
        'controller' => 'PriceGroup@indexAction',
        'disallow' => $nonAdmin
    ],

    '/crm/price-groups/edit/(:var)' => [
        'controller' => 'PriceGroup@editAction',
        'disallow' => $nonAdmin
    ],

    '/crm/price-groups/delete/(:var)' => [
        'controller' => 'PriceGroup@deleteAction',
        'disallow' => $nonAdmin
    ],

    '/crm/price-groups/save' => [
        'controller' => 'PriceGroup@saveAction',
        'disallow' => $nonAdmin
    ],
    
    // Room gallery
    '/crm/architecture/room/gallery/add/(:var)' => [
        'controller' => 'Architecture:RoomTypeGallery@addAction'
    ],

    '/crm/architecture/room/gallery/edit/(:var)' => [
        'controller' => 'Architecture:RoomTypeGallery@editAction'
    ],

    '/crm/architecture/room/gallery/delete/(:var)' => [
        'controller' => 'Architecture:RoomTypeGallery@deleteAction'
    ],

    '/crm/architecture/room/gallery/save' => [
        'controller' => 'Architecture:RoomTypeGallery@saveAction'
    ],

    // Regions
    '/crm/regions' => [
        'controller' => 'Region@indexAction',
        'disallow' => $regular
    ],
    '/crm/regions/view/(:var)' => [
        'controller' => 'Region@districtAction',
        'disallow' => $regular
    ],
    '/crm/regions/edit/(:var)' => [
        'controller' => 'Region@editAction',
        'disallow' => $regular
    ],
    '/crm/regions/delete/(:var)' => [
        'controller' => 'Region@deleteAction',
        'disallow' => $nonAdmin
    ],
    '/crm/regions/add' => [
        'controller' => 'Region@addAction',
        'disallow' => $nonAdmin
    ],
    '/crm/regions/save' => [
        'controller' => 'Region@saveAction',
        'disallow' => $regular
    ],

    // Reviews
    '/crm/reviews' => [
        'controller' => 'Review:Review@indexAction',
    ],
    '/crm/reviews/edit/(:var)' => [
        'controller' => 'Review:Review@editAction',
        'disallow' => [UserService::USER_ROLE_GUEST]
    ],
    '/crm/reviews/delete/(:var)' => [
        'controller' => 'Review:Review@deleteAction',
        'disallow' => [UserService::USER_ROLE_GUEST]
    ],
    '/crm/reviews/save' => [
        'controller' => 'Review:Review@saveAction',
        'disallow' => [UserService::USER_ROLE_GUEST]
    ],
    
    // Review types
    '/crm/review-type' => [
        'controller' => 'Review:ReviewType@indexAction',
        'disallow' => $regular
    ],
    
    '/crm/review-type/add' => [
        'controller' => 'Review:ReviewType@addAction',
        'disallow' => $nonAdmin
    ],

    '/crm/review-type/edit/(:var)' => [
        'controller' => 'Review:ReviewType@editAction',
        'disallow' => $regular
    ],

    '/crm/review-type/delete/(:var)' => [
        'controller' => 'Review:ReviewType@deleteAction',
        'disallow' => $nonAdmin
    ],
    
    '/crm/review-type/save' => [
        'controller' => 'Review:ReviewType@saveAction',
        'disallow' => $regular
    ],
    
    '/crm/feedback' => [
        'controller' => 'Feedback@indexAction'
    ],
    
    '/crm/feedback/submit' => [
        'controller' => 'Feedback@submitAction',
        'disallow' => [UserService::USER_ROLE_GUEST]
    ],
    
    '/crm' => [
        'controller' => 'Crm@indexAction'
    ],

    '/crm/languages/switch/(:var)' => [
        'controller' => 'Language@switchAction'
    ],
    
    '/crm/languages' => [
        'controller' => 'Language@indexAction',
        'disallow' => $nonAdmin
    ],
    
    '/crm/languages/save' => [
        'controller' => 'Language@saveAction',
        'disallow' => $nonAdmin
    ],

    '/crm/languages/edit/(:var)' => [
        'controller' => 'Language@editAction',
        'disallow' => $nonAdmin
    ],
    
    '/crm/languages/delete/(:var)' => [
        'controller' => 'Language@deleteAction',
        'disallow' => $nonAdmin
    ],
    
    '/crm/home' => [
        'controller' => 'Crm@indexAction'
    ],

    '/crm/photo/add' => [
        'controller' => 'Photo@addAction'
    ],

    '/crm/photo/save' => [
        'controller' => 'Photo@saveAction',
        'disallow' => [UserService::USER_ROLE_GUEST],
    ],

    '/crm/photo/edit/(:var)' => [
        'controller' => 'Photo@editAction'
    ],

    '/crm/photo/delete/(:var)' => [
        'controller' => 'Photo@deleteAction',
        'disallow' => [UserService::USER_ROLE_GUEST],
    ],

    '/crm/facility' => [
        'controller' => 'Facility:Grid@indexAction',
        'disallow' => $regular
    ],

    '/crm/facility/checklist' => [
        'controller' => 'Facility:Grid@checklistAction',
        'disallow' => $regular
    ],

    '/crm/facility/category/view/(:var)' => [
        'controller' => 'Facility:Grid@categoryAction',
        'disallow' => $regular
    ],

    // Item data
    '/crm/facility/data/index/(:var)' => [
        'controller' => 'Facility:ItemData@indexAction',
        'disallow' => $regular
    ],

    '/crm/facility/data/add/(:var)' => [
        'controller' => 'Facility:ItemData@addAction',
        'disallow' => $nonAdmin
    ],

    '/crm/facility/data/edit/item/(:var)/(:var)' => [
        'controller' => 'Facility:ItemData@editAction',
        'disallow' => $regular
    ],

    '/crm/facility/data/delete/(:var)' => [
        'controller' => 'Facility:ItemData@deleteAction',
        'disallow' => $nonAdmin
    ],

    '/crm/facility/data/save' => [
        'controller' => 'Facility:ItemData@saveAction',
        'disallow' => $regular
    ],

    // Categories
    '/crm/facility/category/add' => [
        'controller' => 'Facility:Category@addAction',
        'disallow' => $nonAdmin
    ],

    '/crm/facility/category/save' => [
        'controller' => 'Facility:Category@saveAction',
        'disallow' => $regular
    ],

    '/crm/facility/category/edit/(:var)' => [
        'controller' => 'Facility:Category@editAction',
        'disallow' => $regular
    ],

    '/crm/facility/category/delete/(:var)' => [
        'controller' => 'Facility:Category@deleteAction',
        'disallow' => $nonAdmin
    ],

    // Item
    '/crm/facility/item/add' => [
        'controller' => 'Facility:Item@addAction',
        'disallow' => $nonAdmin
    ],

    '/crm/facility/item/save' => [
        'controller' => 'Facility:Item@saveAction',
        'disallow' => $regular
    ],

    '/crm/facility/item/edit/(:var)' => [
        'controller' => 'Facility:Item@editAction',
        'disallow' => $regular
    ],

    '/crm/facility/item/delete/(:var)' => [
        'controller' => 'Facility:Item@deleteAction',
        'disallow' => $nonAdmin
    ],

    // Hotel-specific transactions
    '/crm/transaction/index/(:var)' => [
        'controller' => 'Transaction@indexAction'
    ],
    
    // Clear transactions of current hotel
    '/crm/transaction/clear' => [
        'controller' => 'Transaction@clearAction'
    ],
    
    // All transactions
    '/crm/transaction/list/(:var)' => [
        'controller' => 'Transaction@listAction',
        'disallow' => $nonAdmin
    ],

    '/crm/hotel' => [
        'controller' => 'Hotel@indexAction'
    ],

    '/crm/hotel/save' => [
        'controller' => 'Hotel@saveAction',
        'disallow' => [UserService::USER_ROLE_GUEST],
    ],

    '/crm/services' => [
        'controller' => 'Service@indexAction'
    ],
    
    '/crm/services/edit/(:var)' => [
        'controller' => 'Service@editAction'
    ],

    '/crm/services/delete/(:var)' => [
        'controller' => 'Service@deleteAction',
        'disallow' => [UserService::USER_ROLE_GUEST],
    ],

    '/crm/services/save' => [
        'controller' => 'Service@saveAction',
        'disallow' => [UserService::USER_ROLE_GUEST],
    ],

    // Inventory
    '/crm/inventory' => [
        'controller' => 'Inventory@indexAction'
    ],

    '/crm/inventory/edit/(:var)' => [
        'controller' => 'Inventory@editAction'
    ],

    '/crm/inventory/delete/(:var)' => [
        'controller' => 'Inventory@deleteAction',
        'disallow' => [UserService::USER_ROLE_GUEST],
    ],
    
    '/crm/inventory/save' => [
        'controller' => 'Inventory@saveAction'
    ],

    '/crm/reservation/table/(:var)' => [
        'controller' => 'Reservation@tableAction'
    ],

    '/crm/reservation/find' => [
        'controller' => 'Reservation@findAction'
    ],

    // Reservation services
    '/crm/reservation/services/(:var)' => [
        'controller' => 'ReservationService@indexAction'
    ],
    
    '/crm/reservation/services/do/save' => [
        'controller' => 'ReservationService@saveAction'
    ],
    
    '/crm/reservation/services/do/edit/(:var)' => [
        'controller' => 'ReservationService@editAction'
    ],

    '/crm/reservation/services/do/delete/reservation/(:var)/(:var)' => [
        'controller' => 'ReservationService@deleteAction'
    ],
    
    '/crm/reservation/table/taken/(:var)' => [
        'controller' => 'Reservation@viewTakenAction'
    ],

    // Reservation add
    '/crm/reservation/add/(:var)' => [
        'controller' => 'Reservation@addAction'
    ],

    '/crm/reservation/save' => [
        'controller' => 'Reservation@saveAction',
        'disallow' => [UserService::USER_ROLE_GUEST],
    ],

    '/crm/reservation/view/(:var)' => [
        'controller' => 'Reservation@viewAction'
    ],

    '/crm/reservation/print/(:var)' => [
        'controller' => 'Reservation@printAction'
    ],

    '/crm/reservation/index/(:var)' => [
        'controller' => 'Reservation@indexAction'
    ],

    '/crm/reservation/edit/(:var)' => [
        'controller' => 'Reservation@editAction'
    ],
    
    '/crm/reservation/delete/(:var)' => [
        'controller' => 'Reservation@deleteAction',
        'disallow' => [UserService::USER_ROLE_GUEST],
    ],

    '/crm/reservation/history/room/(:var)' => [
        'controller' => 'Reservation@historyAction'
    ],

    // Room cleaning
    '/crm/room-cleaning' => [
        'controller' => 'RoomCleaning@indexAction'
    ],

    '/crm/room-cleaning/room/(:var)/mark/(:var)' => [
        'controller' => 'RoomCleaning@markAction',
        'disallow' => [UserService::USER_ROLE_GUEST],
    ],

    '/crm/room-cleaning/mark-batch/(:var)' => [
        'controller' => 'RoomCleaning@markBatchAction',
        'disallow' => [UserService::USER_ROLE_GUEST],
    ],

    // Room bed
    '/crm/room-bed' => [
        'controller' => 'RoomBed@indexAction',
        'disallow' => $regular
    ],
    
    '/crm/room-bed/add' => [
        'controller' => 'RoomBed@addAction',
        'disallow' => $nonAdmin
    ],
    
    '/crm/room-bed/edit/(:var)' => [
        'controller' => 'RoomBed@editAction',
        'disallow' => $regular
    ],

    '/crm/room-bed/delete/(:var)' => [
        'controller' => 'RoomBed@deleteAction',
        'disallow' => $nonAdmin
    ],

    '/crm/room-bed/save' => [
        'controller' => 'RoomBed@saveAction',
        'disallow' => $regular
    ],

    '/crm/architecture/room-type' => [
        'controller' => 'Architecture:RoomType@indexAction'
    ],

    '/crm/architecture/room-type/add' => [
        'controller' => 'Architecture:RoomType@addAction'
    ],

    '/crm/architecture/room-type/edit/(:var)' => [
        'controller' => 'Architecture:RoomType@editAction'
    ],

    '/crm/architecture/room-type/delete/(:var)' => [
        'controller' => 'Architecture:RoomType@deleteAction',
        'disallow' => [UserService::USER_ROLE_GUEST],
    ],

    '/crm/architecture/room-type/save' => [
        'controller' => 'Architecture:RoomType@saveAction',
        'disallow' => [UserService::USER_ROLE_GUEST],
    ],

    '/crm/architecture' => [
        'controller' => 'Architecture:Room@indexAction'
    ],

    '/crm/architecture/room/add' => [
        'controller' => 'Architecture:Room@addAction'
    ],

    '/crm/architecture/room/view/(:var)' => [
        'controller' => 'Architecture:Room@viewAction'
    ],

    '/crm/architecture/room/save' => [
        'controller' => 'Architecture:Room@saveAction',
        'disallow' => [UserService::USER_ROLE_GUEST],
    ],

    '/crm/architecture/room/edit/(:var)' => [
        'controller' => 'Architecture:Room@editAction'
    ],

    '/crm/architecture/room/delete/(:var)' => [
        'controller' => 'Architecture:Room@deleteAction',
        'disallow' => [UserService::USER_ROLE_GUEST],
    ],

    // Room inventory
    '/crm/architecture/room/(:var)/inventory' => [
        'controller' => 'Architecture:RoomInventory@indexAction'
    ],

    '/crm/architecture/room/(:var)/inventory/edit/(:var)' => [
        'controller' => 'Architecture:RoomInventory@editAction'
    ],

    '/crm/architecture/room/inventory/save' => [
        'controller' => 'Architecture:RoomInventory@saveAction',
        'disallow' => [UserService::USER_ROLE_GUEST],
    ],

    '/crm/architecture/room/(:var)/inventory/delete/(:var)' => [
        'controller' => 'Architecture:RoomInventory@deleteAction'
    ],

    '/crm/stat/(:var)' => [
        'controller' => 'Stat@indexAction'
    ],

    '/crm/stat/get/report' => [
        'controller' => 'Stat@reportAction'
    ]
];
