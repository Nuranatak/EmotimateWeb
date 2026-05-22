<?php

declare(strict_types=1);

/**
 * @return array<string, array<string, array{label: string, range: string, summary: string}>>
 */
function assessment_interpretation_texts(): array
{
    return [
        'phq9' => [
            'Minimal' => [
                'label'   => 'Minimal Depression',
                'range'   => '0–4',
                'summary' => 'Your responses suggest minimal or very low depressive symptoms at this time. Temporary sadness, stress, or emotional fatigue may still occur occasionally, but these feelings are unlikely to significantly interfere with your daily functioning. You generally appear emotionally stable and capable of managing everyday responsibilities and emotional challenges. This score suggests that symptoms commonly associated with depression are currently mild or infrequent.',
            ],
            'Mild' => [
                'label'   => 'Mild Depression',
                'range'   => '5–9',
                'summary' => 'Your responses suggest mild depressive symptoms that may occasionally affect mood, motivation, or emotional energy. You may experience periods of sadness, low motivation, fatigue, or reduced interest in activities, especially during stressful situations. These symptoms may not severely interfere with daily functioning, but they could still impact emotional well-being and quality of life over time. This score suggests mild emotional distress that may benefit from increased self-care, emotional support, or stress management.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Depression',
                'range'   => '10–14',
                'summary' => 'Your responses suggest a moderate level of depressive symptoms that may noticeably affect emotional well-being, motivation, concentration, sleep, or daily functioning. Feelings such as hopelessness, emotional exhaustion, low self-esteem, or loss of interest may occur more frequently and feel harder to manage. Daily responsibilities, social interaction, or productivity may become emotionally draining or difficult at times. This score suggests that depressive symptoms may be significantly impacting emotional health and should be taken seriously.',
            ],
            'ModeratelySevere' => [
                'label'   => 'Moderately Severe Depression',
                'range'   => '15–19',
                'summary' => 'Your responses suggest moderately severe depressive symptoms that may strongly affect multiple areas of daily life. Persistent sadness, emotional numbness, hopelessness, fatigue, or self-critical thoughts may feel overwhelming or difficult to control. Motivation, concentration, sleep, emotional connection, and overall functioning may be noticeably reduced. This score suggests significant emotional distress and indicates that professional mental health support could be highly beneficial.',
            ],
            'Severe' => [
                'label'   => 'Severe Depression',
                'range'   => '20–27',
                'summary' => 'Your responses suggest severe depressive symptoms that may deeply affect emotional well-being, relationships, motivation, and daily functioning. Feelings of hopelessness, emotional pain, exhaustion, isolation, or loss of interest may feel intense and persistent. Daily tasks, emotional regulation, or maintaining routines may become extremely difficult during this level of distress. This score suggests serious emotional suffering, and seeking support from a qualified mental health professional is strongly recommended.',
            ],
        ],
        'gad7' => [
            'Minimal' => [
                'label'   => 'Minimal Anxiety',
                'range'   => '0–4',
                'summary' => 'Your responses suggest minimal or low levels of anxiety-related symptoms. While stress and worry are natural parts of life, they do not appear to significantly interfere with your emotional balance or daily functioning. You are generally able to manage uncertainty, stress, and emotional pressure in a healthy way. This score suggests stable emotional regulation and relatively low anxiety-related distress.',
            ],
            'Mild' => [
                'label'   => 'Mild Anxiety',
                'range'   => '5–9',
                'summary' => 'Your responses suggest mild anxiety symptoms that may occasionally affect emotional comfort, focus, or relaxation. You may experience periods of excessive worrying, restlessness, tension, or overthinking, particularly during stressful situations. Although these symptoms may feel uncomfortable, they are usually manageable and do not consistently interfere with daily functioning. This score suggests mild emotional stress or anxiety-related sensitivity.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Anxiety',
                'range'   => '10–14',
                'summary' => 'Your responses suggest a moderate level of anxiety that may noticeably impact concentration, relaxation, emotional stability, or everyday functioning. Persistent worry, nervousness, irritability, or difficulty calming your mind may occur regularly and feel emotionally draining. Physical symptoms such as restlessness, tension, sleep difficulties, or mental exhaustion may also become more noticeable. This score suggests that anxiety-related symptoms may significantly affect emotional well-being and stress management.',
            ],
            'Severe' => [
                'label'   => 'Severe Anxiety',
                'range'   => '15–21',
                'summary' => 'Your responses suggest severe anxiety symptoms that may strongly interfere with emotional balance, daily functioning, and overall quality of life. Constant worry, fear, restlessness, or emotional tension may feel overwhelming and difficult to control. Anxiety may significantly affect sleep, concentration, relationships, physical comfort, or the ability to relax and feel emotionally safe. This score suggests intense emotional distress, and professional mental health support may be highly beneficial.',
            ],
        ],
        'emotion_regulation' => [
            'Low' => [
                'label'   => 'Strong Emotional Regulation',
                'range'   => '8–16',
                'summary' => 'Your responses suggest strong emotional awareness and healthy emotional regulation abilities. You are generally capable of identifying emotions, understanding emotional reactions, and expressing feelings in balanced ways. Stressful experiences may still affect you emotionally, but you are usually able to recover and regain emotional balance effectively. This score suggests healthy emotional flexibility, self-awareness, and emotional coping skills.',
            ],
            'Mild' => [
                'label'   => 'Mild Emotional Difficulties',
                'range'   => '17–24',
                'summary' => 'Your responses suggest mild difficulty with emotional awareness or emotional regulation during certain situations. At times, emotions may feel confusing, difficult to express, or somewhat overwhelming during periods of stress. However, you are generally still capable of understanding and managing emotional experiences in a functional way. This score suggests mild emotional sensitivity or occasional difficulty processing emotions effectively.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Emotional Dysregulation',
                'range'   => '25–32',
                'summary' => 'Your responses suggest moderate difficulty understanding, expressing, or regulating emotions consistently. Emotional reactions may sometimes feel intense, overwhelming, or difficult to manage in stressful situations. You may suppress emotions, struggle to communicate emotional needs, or feel emotionally drained after conflict or emotional pressure. This score suggests that emotional regulation challenges may significantly affect stress management, relationships, and emotional well-being.',
            ],
            'High' => [
                'label'   => 'Significant Emotional Regulation Difficulties',
                'range'   => '33–40',
                'summary' => 'Your responses suggest significant challenges with emotional regulation, emotional awareness, or emotional recovery. Strong emotions may feel overwhelming, difficult to control, or emotionally exhausting for long periods of time. Emotional reactions may interfere with relationships, communication, decision-making, or daily functioning. This score suggests that emotional regulation difficulties may deeply affect emotional stability, coping abilities, and overall well-being.',
            ],
        ],
        'mood_stability' => [
            'Low' => [
                'label'   => 'Stable and Balanced Mood',
                'range'   => '8–16',
                'summary' => 'Your responses suggest generally stable mood patterns and healthy emotional balance. Emotional ups and downs may still occur naturally, but they do not appear to significantly disrupt your daily functioning or relationships. You are usually able to recover emotionally after stressful experiences and maintain consistent emotional stability over time. This score suggests healthy emotional resilience and balanced mood regulation.',
            ],
            'Mild' => [
                'label'   => 'Mild Mood Fluctuations',
                'range'   => '17–24',
                'summary' => 'Your responses suggest mild emotional fluctuations that may occasionally affect mood, motivation, or emotional comfort. Certain situations may trigger irritability, emotional sensitivity, or temporary mood instability. However, these emotional changes are usually manageable and do not consistently interfere with daily life. This score suggests mild emotional variability or temporary stress-related mood changes.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Emotional Instability',
                'range'   => '25–32',
                'summary' => 'Your responses suggest moderate mood instability that may noticeably affect emotional balance, energy, relationships, or stress management. Emotional shifts, irritability, emotional exhaustion, or prolonged negative moods may occur more frequently and feel harder to regulate. Stressful situations may strongly influence emotional reactions or overall mood stability. This score suggests that emotional fluctuations may significantly impact emotional well-being and daily functioning.',
            ],
            'High' => [
                'label'   => 'High Emotional and Mood Instability',
                'range'   => '33–40',
                'summary' => 'Your responses suggest high emotional instability and significant difficulty maintaining consistent emotional balance. Mood changes, irritability, emotional exhaustion, or emotional sensitivity may feel intense and difficult to control. Emotional reactions may strongly affect relationships, motivation, productivity, or overall quality of life. This score suggests that emotional instability may deeply impact emotional health, resilience, and everyday functioning.',
            ],
        ],
        'attention_focus' => [
            'Low' => [
                'label'   => 'Strong Attention and Focus Abilities',
                'range'   => '8–16',
                'summary' => 'Your responses suggest healthy attention control, concentration, and mental organization abilities. You are generally able to stay focused on tasks, manage responsibilities, and maintain concentration even during stressful situations. Distractions may occasionally occur, but they usually do not significantly interfere with productivity or daily functioning. This score suggests strong cognitive focus, mental consistency, and effective task management skills.',
            ],
            'Mild' => [
                'label'   => 'Mild Attention Difficulties',
                'range'   => '17–24',
                'summary' => 'Your responses suggest mild difficulties with concentration, focus, or mental organization in certain situations. You may occasionally become distracted, lose focus during long tasks, or struggle with maintaining attention under stress or boredom. However, these challenges are usually manageable and do not consistently disrupt daily functioning. This score suggests mild attentional inconsistency or situational distractibility.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Concentration and Organization Difficulties',
                'range'   => '25–32',
                'summary' => 'Your responses suggest moderate difficulty maintaining attention, organizing tasks, or staying mentally focused consistently. Distractions, forgetfulness, mental overload, or difficulty completing tasks may noticeably affect productivity and daily responsibilities. Long periods of concentration may feel mentally exhausting or frustrating. This score suggests that attention-related difficulties may significantly impact efficiency, stress levels, and everyday functioning.',
            ],
            'High' => [
                'label'   => 'Significant Attention and Focus Difficulties',
                'range'   => '33–40',
                'summary' => 'Your responses suggest significant challenges with concentration, focus, mental organization, or sustained attention. Staying focused on tasks, managing responsibilities, or completing mentally demanding activities may feel consistently difficult or overwhelming. Distractibility, forgetfulness, or mental restlessness may strongly interfere with work, studies, relationships, or daily routines. This score suggests that attention-related difficulties may deeply affect cognitive functioning and overall quality of life.',
            ],
        ],
        'impulsivity_scale' => [
            'Low' => [
                'label'   => 'Strong Self-Control and Impulse Regulation',
                'range'   => '8–16',
                'summary' => 'Your responses suggest healthy self-control, emotional regulation, and decision-making abilities. You are generally capable of pausing before reacting, considering consequences, and managing emotional impulses effectively. Frustration, emotional intensity, or immediate temptations are less likely to control your behavior. This score suggests strong emotional regulation, patience, and behavioral self-management.',
            ],
            'Mild' => [
                'label'   => 'Mild Impulsive Tendencies',
                'range'   => '17–24',
                'summary' => 'Your responses suggest mild impulsive tendencies that may occasionally affect emotional reactions or decision-making. At times, you may react quickly, interrupt others, make emotional decisions, or struggle with patience during stressful situations. However, these behaviors are usually manageable and do not consistently create major problems. This score suggests mild difficulty with emotional impulsivity or short-term frustration tolerance.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Impulsivity and Self-Regulation Difficulties',
                'range'   => '25–32',
                'summary' => 'Your responses suggest moderate impulsivity and noticeable difficulty managing emotional reactions, urges, or decision-making consistently. You may frequently act before fully thinking through consequences or struggle to pause during emotionally intense situations. Impulsivity may affect relationships, productivity, financial choices, or emotional stability over time. This score suggests that self-regulation difficulties may significantly influence daily functioning and emotional balance.',
            ],
            'High' => [
                'label'   => 'High Impulsivity and Difficulty Controlling Reactions',
                'range'   => '33–40',
                'summary' => 'Your responses suggest high impulsivity and major difficulty regulating emotional reactions, urges, or behavioral control. Strong emotions, frustration, boredom, or immediate desires may frequently lead to impulsive decisions or actions. You may experience regret after reacting emotionally or acting without enough reflection. This score suggests that impulsivity and emotional self-control difficulties may deeply affect relationships, stress management, and long-term stability.',
            ],
        ],
        'trauma_intrusive' => [
            'Low' => [
                'label'   => 'Minimal Trauma-Related Symptoms',
                'range'   => '8–16',
                'summary' => 'Your responses suggest minimal intrusive trauma-related symptoms at this time. Distressing memories or emotional triggers may occasionally occur, but they do not appear to strongly interfere with emotional stability or daily functioning. You are generally able to process stressful experiences without persistent emotional disruption. This score suggests relatively healthy recovery and emotional processing abilities.',
            ],
            'Mild' => [
                'label'   => 'Mild Intrusive Stress Responses',
                'range'   => '17–24',
                'summary' => 'Your responses suggest mild trauma-related stress reactions that may occasionally affect emotions, concentration, or emotional comfort. Certain reminders, memories, or stressful situations may trigger temporary emotional distress or intrusive thoughts. However, these reactions are usually manageable and do not consistently disrupt daily life. This score suggests mild emotional sensitivity related to stressful or painful experiences.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Trauma-Related Distress',
                'range'   => '25–32',
                'summary' => 'Your responses suggest moderate trauma-related symptoms that may noticeably affect emotional well-being, concentration, or feelings of safety. Intrusive memories, emotional triggers, nightmares, or distressing thoughts may occur regularly and feel difficult to fully control. Certain situations or reminders may strongly influence mood, stress levels, or emotional stability. This score suggests that unresolved emotional distress may significantly affect daily functioning and emotional recovery.',
            ],
            'High' => [
                'label'   => 'Significant Intrusive and Re-Experiencing Symptoms',
                'range'   => '33–40',
                'summary' => 'Your responses suggest significant trauma-related distress involving intrusive memories, emotional triggers, or re-experiencing symptoms. Painful memories or reminders may feel overwhelming, emotionally intense, or difficult to escape mentally. Emotional safety, concentration, sleep, or overall well-being may be strongly affected by recurring trauma-related reactions. This score suggests that trauma-related symptoms may deeply impact emotional functioning and professional support could be highly beneficial.',
            ],
        ],
        'hypervigilance' => [
            'Low' => [
                'label'   => 'Healthy Emotional Safety and Stress Recovery',
                'range'   => '8–16',
                'summary' => 'Your responses suggest a generally healthy sense of emotional safety and ability to recover from stress. You are usually able to relax, feel emotionally secure, and adapt to uncertainty without excessive fear or tension. Stressful situations may still affect you emotionally, but they do not appear to create persistent hypervigilance or avoidance. This score suggests healthy emotional resilience and nervous system recovery.',
            ],
            'Mild' => [
                'label'   => 'Mild Hypervigilance or Avoidance Patterns',
                'range'   => '17–24',
                'summary' => 'Your responses suggest mild hypervigilance, emotional tension, or avoidance-related behaviors in certain situations. You may occasionally feel overly alert, emotionally cautious, or uncomfortable in environments that feel uncertain or emotionally stressful. However, these reactions are usually manageable and temporary. This score suggests mild emotional sensitivity related to safety, control, or stress.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Trauma-Related Anxiety and Emotional Tension',
                'range'   => '25–32',
                'summary' => 'Your responses suggest moderate emotional hypervigilance and ongoing difficulty feeling fully relaxed or emotionally safe. You may frequently anticipate danger, avoid emotionally triggering situations, or remain mentally “on guard” even in relatively safe environments. Emotional tension, irritability, or anxiety may noticeably affect stress levels, relationships, or emotional comfort. This score suggests that trauma-related stress responses may significantly influence emotional stability and daily functioning.',
            ],
            'High' => [
                'label'   => 'High Hypervigilance, Avoidance, and Emotional Distress',
                'range'   => '33–40',
                'summary' => 'Your responses suggest high levels of hypervigilance, emotional tension, and difficulty experiencing emotional safety consistently. Relaxing or feeling fully secure may feel extremely difficult due to persistent alertness, fear, or emotional sensitivity. Avoidance behaviors, anxiety, emotional numbness, or exaggerated stress responses may strongly interfere with daily life and relationships. This score suggests that chronic trauma-related stress may deeply impact emotional well-being, nervous system regulation, and overall quality of life.',
            ],
        ],
    ];
}
