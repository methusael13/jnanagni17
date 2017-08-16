<?php

namespace jnanagni\Library;

abstract class Constants {
    const CATEGORY_CNT = 5;

    const ID_TECHNICAL = 'tevent';
    const ID_NON_TECHNICAL = 'ntevent';
    const ID_CULTURAL = 'cevent';
    const ID_SPORTS = 'sevent';
    const ID_WORKSHOP = 'workshop';
    const ID_FUN = 'fevent';

    const SPONSOR_CNT = 12;
    const SPONSOR_IMG_PATH = 'res/images/sponsors/';

    const STR_CONVENOR = 'Convenor';
    const STR_CO_CONVENOR = 'Organiser';
    const STR_PRESIDENT = 'President';
    const STR_GEN_SECRETARY = 'General Secretary';

    const CONTACTS_LIST = [
        ['Mohit Chauhan', '8006433030', Constants::STR_PRESIDENT],
        ['Pankaj Singh', '9760860970', Constants::STR_GEN_SECRETARY],
        // The last element indicates a break-point
        ['Abhishek Mishra', '7895500884', Constants::STR_CONVENOR],
        ['Saurabh Suman', '8006776171', Constants::STR_CONVENOR],
        ['Nilesh Gothi', '8936956865', Constants::STR_CONVENOR],
        ['Satyendra Dubey', '7895098673', Constants::STR_CO_CONVENOR],
        ['Rajan Sharma', '7500419073', Constants::STR_CO_CONVENOR],
        ['Ayush Kr Jaiswal', '9455460089', Constants::STR_CO_CONVENOR],
        ['Tapan Pande', '8004851757', Constants::STR_CO_CONVENOR],
        ['D. K. Das', '7455935514', Constants::STR_CO_CONVENOR],
        ['Shivam Kr Singh', '8858096292', Constants::STR_CO_CONVENOR],
        ['Rahul Kumar', '8394866884', Constants::STR_CO_CONVENOR],
        ['Pawan Awana', '9119766437', Constants::STR_CO_CONVENOR]
    ];

    const FACULTY_CONTACTS_LIST = [
        ['Dr. Sunil Panwar', '9897048445', 'Chairman'],
        ['Dr. Mayank Aggarwal', '9719004462', 'Coordinator'],
        ['Mr. Sanjeev Kumar Lambha', '9897058062', 'Organising Secretary'],
        ['Mr. Gajendra Singh Rawat', '9557457799', 'Core Organising Secretary']
    ];

    const DEVELOPERS = [
        ['Methusael Murmu', 'iam.methusael@gmail.com', 'Lead Developer (Website, Full Stack)',
         'res/images/devs/methusael.jpg'],
         ['Vipin Yadav', 'vpnydv10yr@gmail.com', 'Website, Front-End',
         'res/images/devs/vipin.jpg'],
        ['Pranjul Sharma', 'sharma.pranjul1998@gmail.com', 'Lead Developer (Android App)',
         'res/images/devs/pranjul.jpg'],
         ['Amit Singh', 'singhamitch@gmail.com', 'Android App (Front-End)',
         'res/images/devs/amit.jpg']
    ];

    const SOCIAL_IMG_FOLDER = "res/images/social/";
    const SOCIAL_DTL_FOLDER = "storage/social/";

    const SOCIAL_TITLES = [
        "Cashless India", "Blood Donation Camp",
        "Free Education", "Save Energy",
        "Women Empowerment", "Computer Education"
    ];
    const SOCIAL_IMGS = [
        Constants::SOCIAL_IMG_FOLDER . "cashless-india.jpg",
        Constants::SOCIAL_IMG_FOLDER . "blood-don.jpg",
        Constants::SOCIAL_IMG_FOLDER . "free-education.jpg",
        Constants::SOCIAL_IMG_FOLDER . "save-energy.jpg",
        Constants::SOCIAL_IMG_FOLDER . "women-emp.jpg",
        Constants::SOCIAL_IMG_FOLDER . "comp-edu.jpg"
    ];
    const SOCIAL_DTL_PATHS = [
        Constants::SOCIAL_DTL_FOLDER . "cashless-india.cms",
        Constants::SOCIAL_DTL_FOLDER . "blood-don.cms",
        Constants::SOCIAL_DTL_FOLDER . "free-education.cms",
        Constants::SOCIAL_DTL_FOLDER . "save-energy.cms",
        Constants::SOCIAL_DTL_FOLDER . "women-emp.cms",
        Constants::SOCIAL_DTL_FOLDER . "comp-edu.cms"
    ];

    const SCHEDULE = [
        [
            [
                'ts' => '09:00 AM', 'te' => '10:00 AM',
                'list' => ['Inauguration']
            ],
            [
                'ts' => '10:00 AM', 'te' => '01:00 PM',
                'list' => ['Tinkerer', 'App-Titude', 'Inclino', 'Annihilator', 'Third-Vision', 'Abhivyakti', 'Badminton']
            ],
            [
                'ts' => '01:30 PM', 'te' => '04:00 PM',
                'list' => ['Electricio', 'Indoor Games', 'Q-Cognito', 'Enthuse', 'CI-PHER', 'Dance (Auditions)', 'Singing (Auditions)']
            ],
            [
                'ts' => '03:00 PM', 'te' => '05:00 PM',
                'list' => ['LAN Gaming', 'Cricket Keeda', 'Ex-Gesis']
            ],
            [
                'ts' => '05:00 PM', 'te' => '10:00 PM',
                'list' => ['Alumni Meet', 'Sargam', 'Rock Syndrome', 'Fancy Footwork and Group Dance']
            ]
        ],

        [
            [
                'ts' => '09:00 AM', 'te' => '01:00 PM',
                'list' => ['Electricio', 'NOPC', 'Hydroriser', 'Ameliorator', 'Annihilator', 'Abhivyakti', 'Badminton', 'Startup Fair']
            ],
            [
                'ts' => '01:30 PM', 'te' => '04:00 PM',
                'list' => [
                    'Cuandigo', 'Electroguisal', 'Mist Treasure Hunt',
                    'Freedoscrawl', 'Nautankishala', 'Crafts-Villa', 'Concatenation', 'Startup Fair (R2)']
            ],
            [
                'ts' => '03:00 PM', 'te' => '05:00 PM',
                'list' => ['Q-Cognito (R2)', 'Tinkerer (R2)', 'Kalakriti', 'Indoor Games', 'LAN Gaming', 'Cricket Keeda', 'Ex-Gesis (R2)']
            ],
            [
                'ts' => '05:00 PM', 'te' => '10:00 PM',
                'list' => ['Cultural Events', 'Kritika', 'LOL', 'Fancy Footwork', 'Sargam']
            ]
        ],

        [
            [
                'ts' => '09:00 AM', 'te' => '11:30 AM',
                'list' => ['Mist Treasure Hunt', 'Ex-Gesis (R3)', 'Hydroriser', 'Annihilator', 'Badminton', 'Q-Cognito']
            ],
            [
                'ts' => '11:30 AM',
                'list' => ['Valedictory Ceremony']
            ]
        ]
    ];
}
