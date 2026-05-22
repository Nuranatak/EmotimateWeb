<?php

declare(strict_types=1);

function demo_likert_instructions(): string
{
    return <<<'TEXT'
Instructions:

Please rate how much each statement describes you in general, not only how you feel today.

Use the following scale:

1 = Completely disagree
2 = Mostly disagree
3 = Neutral / Sometimes true
4 = Mostly agree
5 = Completely agree

Important Notes:
- There are no right or wrong answers.
- Answer based on your usual thoughts, feelings, and patterns.
- Try to respond honestly for the most accurate result.
TEXT;
}

/**
 * Demo accounts (passwords set in seed script).
 */
function demo_users(): array
{
    return [
        'admin' => [
            'name'  => 'EMOTIMATE Administrator',
            'email' => 'admin@emotimate.com',
            'role'  => 'admin',
            'password_plain' => 'admin123',
        ],
        'member' => [
            'name'  => 'Sarah Mitchell',
            'email' => 'sarah.demo@emotimate.com',
            'role'  => 'user',
            'password_plain' => 'user123',
        ],
    ];
}

/**
 * @return list<array{category: string, title: string, description: string, questions: list<string>}>
 */
function demo_tests_catalog(): array
{
    $instructions = demo_likert_instructions();

    return [
        [
            'category'    => 'Schema Tests',
            'title'       => 'Abandonment / Instability Schema Scale',
            'description' => 'Assesses fears that close relationships will end and emotional support will not continue.',
            'instructions' => $instructions,
            'questions'   => [
                'I worry that people who are important to me will leave me.',
                'I feel that I will eventually lose the interest of people I care about.',
                'I feel insecure about whether people I love will stay with me.',
                'When someone I care about is away, I fear they will not return.',
                'I need other people more than they need me.',
            ],
        ],
        [
            'category'    => 'Schema Tests',
            'title'       => 'Mistrust / Abuse Schema Scale',
            'description' => 'Assesses expectations that others may hurt, manipulate, or take advantage.',
            'instructions' => $instructions,
            'questions'   => [
                'I expect people to take advantage of me if I am not careful.',
                'I am suspicious of other people\'s motives.',
                'I find it hard to trust people completely.',
                'I believe it is important to keep my guard up with most people.',
                'It is only a matter of time before someone betrays me.',
            ],
        ],
        [
            'category'    => 'Depression and Anxiety Scales',
            'title'       => 'Depression Test (PHQ-9)',
            'description' => 'Nine-item depression screening scale (Likert adaptation for EMOTIMATE).',
            'instructions' => $instructions,
            'questions'   => [
                'Little interest or pleasure in doing things.',
                'Feeling down, depressed, or hopeless.',
                'Trouble falling or staying asleep, or sleeping too much.',
                'Feeling tired or having little energy.',
                'Poor appetite or overeating.',
                'Feeling bad about yourself — or that you are a failure.',
                'Trouble concentrating on things.',
                'Moving or speaking slowly, or being fidgety/restless.',
                'Thoughts that you would be better off dead or hurting yourself.',
            ],
        ],
        [
            'category'    => 'Depression and Anxiety Scales',
            'title'       => 'Anxiety Test (GAD-7)',
            'description' => 'Seven-item generalized anxiety screening scale (Likert adaptation).',
            'instructions' => $instructions,
            'questions'   => [
                'Feeling nervous, anxious, or on edge.',
                'Not being able to stop or control worrying.',
                'Worrying too much about different things.',
                'Trouble relaxing.',
                'Being so restless that it is hard to sit still.',
                'Becoming easily annoyed or irritable.',
                'Feeling afraid as if something awful might happen.',
            ],
        ],
        [
            'category'    => 'Emotion and Mood Scales',
            'title'       => 'Emotional Awareness & Regulation Scale',
            'description' => 'Measures emotional awareness and ability to regulate emotions.',
            'instructions' => $instructions,
            'questions'   => [
                'I can usually identify what emotion I am feeling.',
                'I understand why I react emotionally in many situations.',
                'I can calm myself down when I feel upset.',
                'I express my feelings in healthy ways.',
                'I recover reasonably well after emotional stress.',
                'Strong emotions sometimes overwhelm me.',
                'I avoid talking about feelings even when it would help.',
                'I notice physical signs of stress early.',
            ],
        ],
        [
            'category'    => 'Emotion and Mood Scales',
            'title'       => 'Personality Style Inventory',
            'description' => 'Brief self-report inventory exploring common personality style tendencies.',
            'instructions' => $instructions,
            'questions'   => [
                'I enjoy meeting new people and social gatherings.',
                'I prefer planning ahead rather than acting spontaneously.',
                'I stay calm under pressure most of the time.',
                'I appreciate art, ideas, and creative experiences.',
                'I go out of my way to help others.',
                'I pay close attention to details and organization.',
                'I am comfortable taking the lead in groups.',
                'I often reflect deeply before making decisions.',
            ],
        ],
        [
            'category'    => 'Attention and Impulsivity Tests',
            'title'       => 'Attention & Focus Scale',
            'description' => 'Assesses concentration, focus, and task completion.',
            'instructions' => $instructions,
            'questions'   => [
                'I can stay focused on tasks for a reasonable period.',
                'I complete projects I start.',
                'I am easily distracted by notifications or noise.',
                'I forget appointments or deadlines occasionally.',
                'I feel mentally organized when working.',
                'Long tasks feel exhausting to finish.',
            ],
        ],
        [
            'category'    => 'Post-Traumatic Stress Disorder',
            'title'       => 'Trauma Re-Experiencing & Intrusive Thoughts Scale',
            'description' => 'Screens for intrusive memories and trauma-related stress responses.',
            'instructions' => $instructions,
            'questions'   => [
                'Upsetting memories of stressful events come back unexpectedly.',
                'I have disturbing dreams related to difficult experiences.',
                'I feel upset when reminded of painful events.',
                'I avoid places or people that trigger difficult memories.',
                'I feel on guard or watchful in everyday situations.',
                'I am easily startled.',
            ],
        ],
    ];
}

/**
 * Sample completed tests for the demo member (answer values 1–5 per question in order).
 *
 * @return list<array{test_title: string, answers: list<int>}>
 */
function demo_member_sample_results(): array
{
    return [
        [
            'test_title' => 'Abandonment / Instability Schema Scale',
            'answers'    => [4, 3, 4, 3, 4],
        ],
        [
            'test_title' => 'Depression Test (PHQ-9)',
            'answers'    => [1, 2, 1, 2, 1, 1, 2, 1, 1],
        ],
        [
            'test_title' => 'Anxiety Test (GAD-7)',
            'answers'    => [2, 2, 2, 2, 1, 1, 2],
        ],
        [
            'test_title' => 'Emotional Awareness & Regulation Scale',
            'answers'    => [4, 4, 3, 4, 3, 2, 2, 4],
        ],
        [
            'test_title' => 'Personality Style Inventory',
            'answers'    => [2, 2, 3, 2, 3, 2, 2, 3],
        ],
    ];
}
