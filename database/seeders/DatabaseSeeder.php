<?php

namespace Database\Seeders;

use App\Models\Doubt;
use App\Models\DoubtReply;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\StudyMaterial;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@librarianprep.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '9876543210',
                'status' => 'active',
            ]
        );

        $student = User::firstOrCreate(
            ['email' => 'student@librarianprep.com'],
            [
                'name' => 'Rohan Kumar (Student)',
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone' => '9123456789',
                'status' => 'active',
            ]
        );

        // 2. Create Core Subjects & Topics
        $subjectData = [
            [
                'name' => 'Library Classification',
                'icon_class' => 'fas fa-sitemap',
                'description' => 'Comprehensive study of Dewey Decimal Classification (DDC), Universal Decimal Classification (UDC), and Colon Classification (CC).',
                'topics' => [
                    'Principles of Book Classification',
                    'Dewey Decimal Classification (DDC) 19th & 23rd Edition',
                    'Colon Classification (CC) 6th Edition',
                    'Notation, Facet Analysis & Phase Relations',
                ]
            ],
            [
                'name' => 'Cataloguing & Metadata',
                'icon_class' => 'fas fa-tags',
                'description' => 'Anglo-American Cataloguing Rules (AACR-2), Classified Catalogue Code (CCC), MARC21 format, and Dublin Core Metadata.',
                'topics' => [
                    'AACR-2 Rules & Headings',
                    'Classified Catalogue Code (CCC) Entries',
                    'MARC 21 Format & Tagging',
                    'ISBD & Metadata Standards',
                ]
            ],
            [
                'name' => 'Library Automation & Software',
                'icon_class' => 'fas fa-desktop',
                'description' => 'Integrated Library Management Software (ILMS) such as Koha, SOUL 3.0, DSpace, and RFID in libraries.',
                'topics' => [
                    'Koha ILS Architecture & Modules',
                    'SOUL 3.0 & Digital Repositories',
                    'RFID & Barcode Technology in Libraries',
                    'Open Source Software in Libraries',
                ]
            ],
            [
                'name' => 'Information Sources & Services',
                'icon_class' => 'fas fa-info-circle',
                'description' => 'Primary, Secondary, Tertiary sources, Reference services, Document Delivery Service (DDS), and CAS/SDI.',
                'topics' => [
                    'Primary, Secondary & Tertiary Sources',
                    'Current Awareness Service (CAS) & SDI',
                    'Indexing & Abstracting Services',
                    'E-Resources & Consortium (e-ShodhSindhu)',
                ]
            ],
            [
                'name' => 'Library Management & Laws',
                'icon_class' => 'fas fa-building',
                'description' => 'POSDCORB management functions, Library Legislation in India, Copyright Act, and Five Laws of Library Science.',
                'topics' => [
                    'Five Laws of Library Science (S.R. Ranganathan)',
                    'Library Public Relations & Budgeting',
                    'Library Acts & Legislation in India',
                    'Copyright & Intellectual Property Rights (IPR)',
                ]
            ],
        ];

        foreach ($subjectData as $sData) {
            $subject = Subject::create([
                'name' => $sData['name'],
                'slug' => Str::slug($sData['name']),
                'icon_class' => $sData['icon_class'],
                'description' => $sData['description'],
                'is_active' => true,
            ]);

            foreach ($sData['topics'] as $tName) {
                Topic::create([
                    'subject_id' => $subject->id,
                    'name' => $tName,
                    'slug' => Str::slug($tName),
                    'description' => "Detailed study module on {$tName}.",
                    'is_active' => true,
                ]);
            }
        }

        $classificationSub = Subject::where('slug', 'library-classification')->first();
        $cataloguingSub = Subject::where('slug', 'cataloguing-metadata')->first();
        $automationSub = Subject::where('slug', 'library-automation-software')->first();

        // 3. Create Sample Video Lectures
        Video::create([
            'subject_id' => $classificationSub->id,
            'title' => 'Colon Classification (CC 6th Ed) Basic Concepts & Notation',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_id' => 'dQw4w9WgXcQ',
            'thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg',
            'description' => 'In-depth explanation of PMEST facets and phase relations in Colon Classification by Dr. S.R. Ranganathan.',
            'is_active' => true,
        ]);

        Video::create([
            'subject_id' => $cataloguingSub->id,
            'title' => 'AACR-2 vs CCC Main Entry Format Explained',
            'youtube_url' => 'https://www.youtube.com/watch?v=3JZ_D3ELwOQ',
            'youtube_id' => '3JZ_D3ELwOQ',
            'thumbnail_url' => 'https://img.youtube.com/vi/3JZ_D3ELwOQ/hqdefault.jpg',
            'description' => 'Comparison of main entry structure between Anglo-American Cataloguing Rules 2 and Classified Catalogue Code.',
            'is_active' => true,
        ]);

        // 4. Create Sample PDF Study Notes
        StudyMaterial::create([
            'subject_id' => $classificationSub->id,
            'title' => 'DDC 19th & 23rd Edition Complete Revision Notes PDF',
            'description' => 'Comprehensive revision notes detailing main classes, tables 1 to 7, and number building examples in DDC.',
            'file_path' => 'study_materials/sample_ddc_notes.pdf',
            'file_size' => 1048576,
            'downloads_count' => 142,
            'is_active' => true,
        ]);

        StudyMaterial::create([
            'subject_id' => $cataloguingSub->id,
            'title' => 'AACR-2 Main & Added Entries Rules PDF Handbook',
            'description' => 'Quick reference PDF containing single author, joint author, pseudonymous, and corporate authorship rules.',
            'file_path' => 'study_materials/sample_aacr2_notes.pdf',
            'file_size' => 2097152,
            'downloads_count' => 210,
            'is_active' => true,
        ]);

        // 5. Create Full Mock Test Series with 20 Real MCQs
        $mockQuiz = Quiz::create([
            'subject_id' => null, // Full Mock Test
            'title' => 'KVS / NVS Librarian Full Mock Test #1',
            'slug' => 'kvs-nvs-librarian-full-mock-test-1',
            'type' => 'mock',
            'duration_minutes' => 30,
            'pass_percentage' => 50,
            'marks_per_question' => 1.0,
            'negative_marking_per_question' => 0.25,
            'description' => 'Complete 20-question full length practice paper for KVS & NVS Librarian posts covering classification, cataloguing, automation & information sources.',
            'is_active' => true,
        ]);

        $mcqs = [
            [
                'question' => '1. Who is known as the Father of Library Science in India?',
                'explanation' => 'Dr. Shiyali Ramamrita Ranganathan (S.R. Ranganathan) is universally recognized as the Father of Library Science in India.',
                'options' => ['Dr. S.R. Ranganathan', 'B.S. Kesavan', 'Melvil Dewey', 'C.A. Cutter'],
                'correct' => 0
            ],
            [
                'question' => '2. The Five Laws of Library Science were first published in which year?',
                'explanation' => 'Dr. S.R. Ranganathan published the Five Laws of Library Science in 1931.',
                'options' => ['1928', '1931', '1933', '1947'],
                'correct' => 1
            ],
            [
                'question' => '3. Dewey Decimal Classification (DDC) was designed by Melvil Dewey in which year?',
                'explanation' => 'The 1st edition of DDC was published anonymously by Melvil Dewey in 1876.',
                'options' => ['1876', '1895', '1905', '1933'],
                'correct' => 0
            ],
            [
                'question' => '4. Which fundamental category is represented by ":" (colon) symbol in Colon Classification (CC 6th Edition)?',
                'explanation' => 'In Colon Classification (CC 6th ed.), Energy (E) facet is preceded by the colon (:) indicator digit.',
                'options' => ['Personality (P)', 'Matter (M)', 'Energy (E)', 'Space (S)'],
                'correct' => 2
            ],
            [
                'question' => '5. AACR-2 (Anglo-American Cataloguing Rules, Second Edition) was published in which year?',
                'explanation' => 'Anglo-American Cataloguing Rules, Second Edition (AACR-2) was published in 1978.',
                'options' => ['1967', '1978', '1988', '1998'],
                'correct' => 1
            ],
            [
                'question' => '6. Koha is an open-source Integrated Library Management System originally developed in which country?',
                'explanation' => 'Koha was created in 1999 by Katipo Communications for Horowhenua Library Trust in New Zealand.',
                'options' => ['United States', 'United Kingdom', 'New Zealand', 'India'],
                'correct' => 2
            ],
            [
                'question' => '7. What does MARC stand for in library cataloguing?',
                'explanation' => 'MARC stands for Machine-Readable Cataloging, created by Henriette Avram at the Library of Congress.',
                'options' => [
                    'Machine-Readable Cataloging',
                    'Master Archival Record Center',
                    'Media Automated Retrieval Code',
                    'Machine Analytical Record Classification'
                ],
                'correct' => 0
            ],
            [
                'question' => '8. Which of the following is considered a Primary Source of Information?',
                'explanation' => 'Patents, research periodicals, dissertations, and conference proceedings are primary sources.',
                'options' => ['Textbook', 'Patent', 'Encyclopedia', 'Abstracting Journal'],
                'correct' => 1
            ],
            [
                'question' => '9. National Library of India is located in which city?',
                'explanation' => 'The National Library of India is situated at Belvedere Estate in Kolkata, West Bengal.',
                'options' => ['New Delhi', 'Kolkata', 'Chennai', 'Mumbai'],
                'correct' => 1
            ],
            [
                'question' => '10. Delivery of Books and Newspapers Act in India was passed in which year?',
                'explanation' => 'The Delivery of Books Act was enacted in 1954 and amended in 1956 to include newspapers.',
                'options' => ['1948', '1954', '1967', '1972'],
                'correct' => 1
            ],
            [
                'question' => '11. INFLIBNET Autonomous Inter-University Centre of UGC is located in which city?',
                'explanation' => 'Information and Library Network (INFLIBNET) Centre is situated in Infocity, Gandhinagar, Gujarat.',
                'options' => ['New Delhi', 'Bengaluru', 'Gandhinagar', 'Ahmedabad'],
                'correct' => 2
            ],
            [
                'question' => '12. International Standard Serial Number (ISSN) consists of how many digits?',
                'explanation' => 'ISSN is an 8-digit code used to identify serial publications such as journals and magazines.',
                'options' => ['8 Digits', '10 Digits', '13 Digits', '16 Digits'],
                'correct' => 0
            ],
            [
                'question' => '13. International Standard Book Number (ISBN) changed from 10 digits to 13 digits in which year?',
                'explanation' => 'The International ISBN Agency officially transitioned from 10-digit to 13-digit ISBN format on 1 January 2007.',
                'options' => ['2000', '2005', '2007', '2010'],
                'correct' => 2
            ],
            [
                'question' => '14. Which Law of Library Science strongly supports the Open Access System in libraries?',
                'explanation' => 'The Third Law "Every Book its Reader" emphasizes open shelf access so readers can browse books directly.',
                'options' => ['First Law', 'Second Law', 'Third Law', 'Fourth Law'],
                'correct' => 2
            ],
            [
                'question' => '15. What does RDA stand for in modern library cataloguing standards?',
                'explanation' => 'RDA stands for Resource Description and Access, designed to replace AACR-2 for digital environment cataloguing.',
                'options' => [
                    'Resource Description and Access',
                    'Rational Data Architecture',
                    'Record Digital Automation',
                    'Retrieval Data Alignment'
                ],
                'correct' => 0
            ],
            [
                'question' => '16. Dublin Core Metadata element set consists of how many core elements?',
                'explanation' => 'The basic Dublin Core Metadata Element Set consists of 15 standardized elements (Title, Creator, Subject, etc.).',
                'options' => ['10 Elements', '12 Elements', '15 Elements', '20 Elements'],
                'correct' => 2
            ],
            [
                'question' => '17. DSpace open-source digital repository software was jointly developed by which institutions?',
                'explanation' => 'DSpace was jointly developed by Massachusetts Institute of Technology (MIT) Libraries and Hewlett-Packard (HP) Labs.',
                'options' => ['MIT & HP Labs', 'CERN & NASA', 'UNESCO & IFLA', 'Harvard & IBM'],
                'correct' => 0
            ],
            [
                'question' => '18. World Book and Copyright Day is celebrated globally on which date?',
                'explanation' => 'UNESCO declared 23 April as World Book and Copyright Day to honor famous authors like William Shakespeare.',
                'options' => ['8th September', '23rd April', '14th November', '12th August'],
                'correct' => 1
            ],
            [
                'question' => '19. Raja Rammohun Roy Library Foundation (RRRLF) was established by Government of India in which year?',
                'explanation' => 'RRRLF was established in 1972 at Kolkata to support public library development in India.',
                'options' => ['1962', '1972', '1982', '1992'],
                'correct' => 1
            ],
            [
                'question' => '20. Which classification scheme uses Pure Notation consisting exclusively of Arabic numerals?',
                'explanation' => 'Dewey Decimal Classification (DDC) uses pure notation consisting solely of Indo-Arabic numerals (000-999).',
                'options' => ['Colon Classification', 'Dewey Decimal Classification', 'Universal Decimal Classification', 'Library of Congress Classification'],
                'correct' => 1
            ],
        ];

        foreach ($mcqs as $index => $mcq) {
            $question = Question::create([
                'quiz_id' => $mockQuiz->id,
                'question_text' => $mcq['question'],
                'explanation' => $mcq['explanation'],
                'marks' => 1.0,
                'order' => $index + 1,
            ]);

            foreach ($mcq['options'] as $optIndex => $optText) {
                QuizOption::create([
                    'question_id' => $question->id,
                    'option_text' => $optText,
                    'is_correct' => ($optIndex === $mcq['correct']),
                    'order' => $optIndex + 1,
                ]);
            }
        }

        // 6. Create Subject-specific Quiz
        $classQuiz = Quiz::create([
            'subject_id' => $classificationSub->id,
            'title' => 'Library Classification Special Quiz',
            'slug' => 'library-classification-special-quiz',
            'type' => 'subject',
            'duration_minutes' => 10,
            'pass_percentage' => 40,
            'marks_per_question' => 1.0,
            'negative_marking_per_question' => 0.25,
            'description' => 'Subject-wise test focusing on DDC, UDC, CC, and book classification rules.',
            'is_active' => true,
        ]);

        foreach (array_slice($mcqs, 0, 4) as $index => $mcq) {
            $question = Question::create([
                'quiz_id' => $classQuiz->id,
                'question_text' => $mcq['question'],
                'explanation' => $mcq['explanation'],
                'marks' => 1.0,
                'order' => $index + 1,
            ]);

            foreach ($mcq['options'] as $optIndex => $optText) {
                QuizOption::create([
                    'question_id' => $question->id,
                    'option_text' => $optText,
                    'is_correct' => ($optIndex === $mcq['correct']),
                    'order' => $optIndex + 1,
                ]);
            }
        }

        // 7. Create Sample Student Doubt & Admin Reply
        $doubt = Doubt::create([
            'user_id' => $student->id,
            'subject_id' => $cataloguingSub->id,
            'title' => 'Difference between AACR2 and CCC Main Entry?',
            'description' => 'Respected Teacher, please clarify the main header structural difference between AACR2 cataloguing rules and Dr. Ranganathan\'s CCC.',
            'status' => 'replied',
        ]);

        DoubtReply::create([
            'doubt_id' => $doubt->id,
            'user_id' => $admin->id,
            'reply_text' => 'In AACR2, the Main Entry consists of Title/Statement of responsibility, edition, publication, physical description, and series. In CCC, the Main Entry has 6 sections: Leading Section (Call No), Heading Section (Author), Title Section, Note Section, Accession No, and Tracing.',
        ]);
    }
}
