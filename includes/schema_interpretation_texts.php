<?php

declare(strict_types=1);

/**
 * Per-schema, per-tier result narratives (8–40 scale).
 *
 * @return array<string, array<string, array{label: string, range: string, summary: string}>>
 */
function schema_interpretation_texts(): array
{
    return [
        'abandonment' => [
            'Low' => [
                'label'   => 'Low Schema Activation',
                'range'   => '8–16',
                'summary' => 'You generally feel emotionally secure in close relationships and are less likely to fear abandonment or rejection. Temporary distance or conflict in relationships usually does not create intense anxiety for you. You are more likely to trust that important people in your life will remain emotionally available over time. This score suggests that feelings of stability, attachment, and emotional safety are generally well developed.',
            ],
            'Mild' => [
                'label'   => 'Mild Schema Activation',
                'range'   => '17–24',
                'summary' => 'You may occasionally worry about losing important relationships, especially during emotionally stressful situations. At times, emotional distance, delayed responses, or conflict may create insecurity or overthinking. However, these fears are usually manageable and do not consistently control your behavior. This score suggests mild sensitivity to rejection or emotional instability, particularly during vulnerable periods.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Schema Activation',
                'range'   => '25–32',
                'summary' => 'Concerns about abandonment or emotional instability may noticeably affect your relationships and emotional reactions. You may seek reassurance more often, fear being replaced, or become emotionally reactive when someone feels distant. Situations involving conflict, uncertainty, or separation may trigger anxiety, insecurity, or emotional dependence. This score suggests that fear of abandonment may play an important role in your emotional patterns and attachment style.',
            ],
            'High' => [
                'label'   => 'High Schema Activation',
                'range'   => '33–40',
                'summary' => 'Fear of abandonment or emotional loss may strongly influence how you think, feel, and behave in relationships. Even stable relationships may feel emotionally unsafe or uncertain at times. You may become highly sensitive to rejection, overanalyze emotional signals, or fear being left alone. This score suggests that relationship insecurity and emotional instability may significantly impact emotional well-being, attachment patterns, and trust in close relationships.',
            ],
        ],
        'approval_seeking' => [
            'Low' => [
                'label'   => 'Low Schema Activation',
                'range'   => '8–16',
                'summary' => 'Your self-worth is generally based on internal values rather than external approval or validation. You are usually able to make decisions without excessive concern about how others will judge you. While appreciation from others may feel good, it is not the primary source of your confidence or identity. This score suggests a relatively stable sense of self and emotional independence from external opinions.',
            ],
            'Mild' => [
                'label'   => 'Mild Schema Activation',
                'range'   => '17–24',
                'summary' => 'You may sometimes care strongly about how others perceive you or seek reassurance during uncertain situations. Social approval, recognition, or acceptance may occasionally influence your confidence or choices. However, these concerns usually remain balanced and manageable. This score suggests mild sensitivity to criticism, rejection, or external evaluation.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Schema Activation',
                'range'   => '25–32',
                'summary' => 'External validation may significantly affect your self-esteem, emotional state, or decision-making. You may frequently seek approval, avoid disapproval, or adapt your behavior to gain acceptance from others. Criticism, rejection, or lack of recognition may feel emotionally painful or discouraging. This score suggests that self-worth may depend heavily on external feedback and social acceptance.',
            ],
            'High' => [
                'label'   => 'High Schema Activation',
                'range'   => '33–40',
                'summary' => 'You may strongly rely on approval, admiration, or recognition from others to feel valuable or emotionally secure. Fear of rejection or criticism may lead you to suppress your authentic thoughts, emotions, or preferences. Social image, achievement, or acceptance may become central to your sense of identity and self-esteem. This score suggests that external validation may play a dominant role in emotional well-being and self-worth.',
            ],
        ],
        'defectiveness' => [
            'Low' => [
                'label'   => 'Low Schema Activation',
                'range'   => '8–16',
                'summary' => 'You generally feel comfortable with who you are and do not strongly view yourself as flawed or unworthy. Mistakes or imperfections are less likely to define your identity or self-esteem. You are more capable of accepting yourself without excessive shame or self-criticism. This score suggests relatively healthy self-worth and emotional self-acceptance.',
            ],
            'Mild' => [
                'label'   => 'Mild Schema Activation',
                'range'   => '17–24',
                'summary' => 'You may occasionally feel insecure, inadequate, or worried about how others perceive you. Certain situations may trigger self-doubt, embarrassment, or fear of rejection. However, these feelings are usually temporary and do not consistently dominate your self-image. This score suggests mild vulnerability to shame-related thoughts or insecurities.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Schema Activation',
                'range'   => '25–32',
                'summary' => 'Feelings of inadequacy, shame, or fear of rejection may significantly affect self-esteem and emotional well-being. You may hide parts of yourself, avoid vulnerability, or worry that others would reject the “real” you. Criticism or perceived failure may strongly impact confidence and emotional balance. This score suggests that self-worth and emotional security may be heavily influenced by shame-related beliefs.',
            ],
            'High' => [
                'label'   => 'High Schema Activation',
                'range'   => '33–40',
                'summary' => 'You may strongly believe that you are fundamentally flawed, unlovable, or emotionally inadequate. Fear of rejection or exposure may lead to emotional withdrawal, hiding vulnerability, or constant self-criticism. Even positive feedback from others may feel difficult to fully believe or accept. This score suggests that shame-based beliefs may deeply affect relationships, self-esteem, and emotional health.',
            ],
        ],
        'dependence' => [
            'Low' => [
                'label'   => 'Low Schema Activation',
                'range'   => '8–16',
                'summary' => 'You generally trust your ability to manage responsibilities, solve problems, and function independently. Difficult situations may feel stressful, but you usually believe you can handle them effectively. You are capable of making decisions without excessive dependence on reassurance from others. This score suggests healthy confidence in personal competence and independence.',
            ],
            'Mild' => [
                'label'   => 'Mild Schema Activation',
                'range'   => '17–24',
                'summary' => 'You may occasionally doubt your abilities or seek reassurance before making important decisions. During stressful situations, you might feel temporarily overwhelmed or uncertain about handling responsibilities alone. However, you are generally able to regain confidence and function independently. This score suggests mild insecurity related to self-confidence or independence.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Schema Activation',
                'range'   => '25–32',
                'summary' => 'You may frequently feel uncertain about your ability to manage responsibilities or make decisions independently. Fear of making mistakes may lead to excessive reassurance-seeking or dependence on others for support and guidance. Challenging situations may feel emotionally overwhelming or difficult to handle alone. This score suggests that self-confidence and independence may be significantly affected by feelings of incompetence.',
            ],
            'High' => [
                'label'   => 'High Schema Activation',
                'range'   => '33–40',
                'summary' => 'You may strongly believe that you are incapable of functioning independently or handling life effectively on your own. Responsibilities, uncertainty, or decision-making may create intense anxiety, helplessness, or emotional dependence. You may rely heavily on others for reassurance, direction, or emotional security. This score suggests that fear of incompetence or inability may strongly impact confidence, autonomy, and daily functioning.',
            ],
        ],
        'emotional_deprivation' => [
            'Low' => [
                'label'   => 'Low Schema Activation',
                'range'   => '8–16',
                'summary' => 'You generally feel emotionally supported, understood, and cared for in your relationships. You are more likely to believe that others can provide comfort, empathy, and emotional connection when needed. Emotional closeness usually feels safe and accessible to you. This score suggests healthy expectations regarding emotional support and connection.',
            ],
            'Mild' => [
                'label'   => 'Mild Schema Activation',
                'range'   => '17–24',
                'summary' => 'You may occasionally feel emotionally misunderstood or unsupported, especially during stressful periods. At times, you might feel that others do not fully recognize your emotional needs or inner experiences. However, these feelings are usually temporary and manageable. This score suggests mild sensitivity to emotional disconnection or unmet emotional needs.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Schema Activation',
                'range'   => '25–32',
                'summary' => 'You may frequently feel emotionally unfulfilled or believe that others cannot truly understand or support you. Emotional loneliness or disappointment may affect your relationships and emotional well-being. You may hesitate to express emotional needs because you expect them to be ignored or unmet. This score suggests that emotional deprivation may significantly influence attachment patterns and emotional satisfaction.',
            ],
            'High' => [
                'label'   => 'High Schema Activation',
                'range'   => '33–40',
                'summary' => 'You may strongly believe that your emotional needs will never be fully met by others. Relationships may feel emotionally distant, unsatisfying, or lacking genuine understanding and care. You might experience chronic emotional loneliness even when surrounded by people. This score suggests that emotional deprivation may deeply affect intimacy, trust, and emotional connection with others.',
            ],
        ],
        'emotional_inhibition' => [
            'Low' => [
                'label'   => 'Low Schema Activation',
                'range'   => '8–16',
                'summary' => 'You are generally comfortable expressing emotions in healthy and balanced ways. Vulnerability, emotional openness, and communication are less likely to feel threatening or unsafe. You can usually express both positive and negative emotions appropriately. This score suggests healthy emotional flexibility and openness.',
            ],
            'Mild' => [
                'label'   => 'Mild Schema Activation',
                'range'   => '17–24',
                'summary' => 'You may sometimes hold back emotions to avoid discomfort, conflict, or vulnerability. Certain emotions may feel difficult to express openly, especially in emotionally sensitive situations. However, you are still generally capable of emotional communication when necessary. This score suggests mild emotional restraint or discomfort with vulnerability.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Schema Activation',
                'range'   => '25–32',
                'summary' => 'You may frequently suppress emotions, avoid vulnerability, or struggle to communicate emotional needs openly. Emotional expression may feel unsafe, uncomfortable, or risky in relationships. Others may sometimes perceive you as emotionally distant, controlled, or difficult to read. This score suggests that emotional inhibition may noticeably affect intimacy, stress management, and emotional connection.',
            ],
            'High' => [
                'label'   => 'High Schema Activation',
                'range'   => '33–40',
                'summary' => 'You may strongly avoid emotional expression and consistently suppress vulnerable thoughts or feelings. Showing emotions may feel unsafe, embarrassing, or emotionally threatening. This pattern may lead to emotional isolation, internal stress, or difficulty forming deep emotional connections. This score suggests that emotional suppression may significantly impact emotional well-being, relationships, and self-expression.',
            ],
        ],
        'enmeshment' => [
            'Low' => [
                'label'   => 'Low Schema Activation',
                'range'   => '8–16',
                'summary' => 'You generally maintain a healthy balance between emotional closeness and personal independence. You are able to form close relationships while still preserving your own identity, values, and personal direction. Emotional attachment does not usually interfere with your sense of individuality. This score suggests healthy emotional boundaries and self-development.',
            ],
            'Mild' => [
                'label'   => 'Mild Schema Activation',
                'range'   => '17–24',
                'summary' => 'You may sometimes rely heavily on emotionally important people for reassurance, direction, or emotional stability. Certain relationships may influence your decisions or emotional state more strongly than you would prefer. However, you are usually able to maintain an independent sense of self overall. This score suggests mild difficulty balancing closeness and independence.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Schema Activation',
                'range'   => '25–32',
                'summary' => 'Your identity, emotional stability, or decision-making may be strongly influenced by close relationships or family dynamics. You may struggle to separate your own needs, emotions, or goals from those of important people in your life. Independence may feel emotionally uncomfortable or associated with guilt and anxiety. This score suggests that emotional overinvolvement may significantly affect self-development and autonomy.',
            ],
            'High' => [
                'label'   => 'High Schema Activation',
                'range'   => '33–40',
                'summary' => 'You may strongly depend on emotionally close relationships to define your identity, emotional security, or sense of direction. Separation, independence, or emotional distance may feel extremely uncomfortable or threatening. You may struggle to recognize your own personal identity outside of important relationships. This score suggests that emotional enmeshment may deeply impact individuality, boundaries, and emotional independence.',
            ],
        ],
        'entitlement' => [
            'Low' => [
                'label'   => 'Low Schema Activation',
                'range'   => '8–16',
                'summary' => 'You generally respect fairness, boundaries, and mutual responsibility in relationships and daily life. You are usually capable of balancing your own needs with consideration for others. Rules, limits, and compromise are not likely to feel excessively frustrating or restrictive. This score suggests healthy self-awareness and interpersonal balance.',
            ],
            'Mild' => [
                'label'   => 'Mild Schema Activation',
                'range'   => '17–24',
                'summary' => 'You may occasionally expect special treatment or become frustrated when your needs are not prioritized. At times, you may struggle with patience, compromise, or accepting limitations. However, these reactions are generally manageable and situational. This score suggests mild tendencies toward self-prioritization or frustration with restrictions.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Schema Activation',
                'range'   => '25–32',
                'summary' => 'You may frequently prioritize your own needs, preferences, or expectations over those of others. Limits, criticism, or compromise may feel emotionally difficult or frustrating. You may struggle with accepting situations where you are not in control or receiving special consideration. This score suggests that entitlement-related patterns may noticeably affect relationships, flexibility, and emotional regulation.',
            ],
            'High' => [
                'label'   => 'High Schema Activation',
                'range'   => '33–40',
                'summary' => 'You may strongly believe that your needs, opinions, or desires should take priority over rules, limits, or the needs of others. Frustration tolerance may be low when situations do not go according to your expectations. Relationships may become strained due to difficulty with compromise, empathy, or respecting boundaries. This score suggests that grandiosity or entitlement patterns may significantly impact interpersonal functioning and emotional balance.',
            ],
        ],
        'failure' => [
            'Low' => [
                'label'   => 'Low Schema Activation',
                'range'   => '8–16',
                'summary' => 'You generally view yourself as capable, competent, and able to succeed in important areas of life. Challenges and setbacks are less likely to define your identity or self-worth. You are usually able to recognize your abilities and potential realistically. This score suggests healthy confidence in achievement and personal capability.',
            ],
            'Mild' => [
                'label'   => 'Mild Schema Activation',
                'range'   => '17–24',
                'summary' => 'You may occasionally doubt your abilities or compare yourself negatively to others during stressful situations. Certain failures or setbacks may temporarily affect confidence or motivation. However, these thoughts are usually manageable and do not consistently define your self-image. This score suggests mild insecurity regarding achievement or competence.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Schema Activation',
                'range'   => '25–32',
                'summary' => 'You may frequently fear failure, feel inadequate compared to others, or doubt your ability to succeed. These beliefs may reduce confidence, increase avoidance, or create pressure around achievement-related situations. You may focus heavily on mistakes, weaknesses, or perceived shortcomings. This score suggests that fear of failure may significantly affect motivation, self-esteem, and performance.',
            ],
            'High' => [
                'label'   => 'High Schema Activation',
                'range'   => '33–40',
                'summary' => 'You may strongly believe that you are fundamentally less capable, less intelligent, or less successful than others. Achievement-related situations may trigger intense anxiety, hopelessness, or feelings of inadequacy. Fear of failure may lead to avoidance, self-criticism, or difficulty pursuing goals confidently. This score suggests that failure-related beliefs may deeply impact confidence, motivation, and long-term emotional well-being.',
            ],
        ],
        'self_control' => [
            'Low' => [
                'label'   => 'Low Schema Activation',
                'range'   => '8–16',
                'summary' => 'You generally manage impulses, emotions, and responsibilities in a balanced and healthy way. Long-term goals are usually more important to you than temporary comfort or immediate gratification. You are capable of tolerating frustration and maintaining discipline when necessary. This score suggests healthy self-control, emotional regulation, and personal responsibility.',
            ],
            'Mild' => [
                'label'   => 'Mild Schema Activation',
                'range'   => '17–24',
                'summary' => 'You may occasionally struggle with patience, discipline, or controlling emotional reactions during stressful situations. At times, procrastination, impulsive decisions, or avoidance of discomfort may interfere with productivity or responsibilities. However, these behaviors are usually temporary and manageable. This score suggests mild difficulties with consistency, self-control, or frustration tolerance.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Schema Activation',
                'range'   => '25–32',
                'summary' => 'Impulsivity, emotional reactivity, or difficulty maintaining discipline may noticeably affect your daily functioning and long-term goals. You may avoid tasks that feel difficult, uncomfortable, or emotionally demanding. Immediate emotional relief or short-term pleasure may sometimes take priority over responsibility or long-term consequences. This score suggests that self-control and emotional regulation difficulties may significantly impact productivity, relationships, or stress management.',
            ],
            'High' => [
                'label'   => 'High Schema Activation',
                'range'   => '33–40',
                'summary' => 'You may strongly struggle with emotional impulsivity, frustration tolerance, or maintaining consistent discipline and responsibility. Difficult emotions, boredom, or discomfort may feel extremely hard to tolerate without immediate relief or escape. Impulsive choices, avoidance behaviors, or emotional reactions may repeatedly create problems in important areas of life. This score suggests that self-regulation difficulties may deeply affect stability, emotional balance, and long-term functioning.',
            ],
        ],
        'mistrust' => [
            'Low' => [
                'label'   => 'Low Schema Activation',
                'range'   => '8–16',
                'summary' => 'You generally feel emotionally safe trusting others and forming close relationships. While you recognize that people are imperfect, you do not strongly expect betrayal, manipulation, or emotional harm. Vulnerability and emotional openness are usually manageable for you. This score suggests healthy interpersonal trust and emotional safety.',
            ],
            'Mild' => [
                'label'   => 'Mild Schema Activation',
                'range'   => '17–24',
                'summary' => 'You may occasionally question others\' intentions or become cautious in emotionally vulnerable situations. Past disappointments or negative experiences may sometimes affect your ability to fully trust people. However, these concerns are usually manageable and do not dominate relationships. This score suggests mild sensitivity to betrayal, disappointment, or emotional harm.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Schema Activation',
                'range'   => '25–32',
                'summary' => 'Trust issues may significantly affect emotional openness, relationships, and feelings of safety around others. You may frequently expect manipulation, betrayal, dishonesty, or emotional harm from people. Emotional vulnerability may feel risky, leading to guarded behavior or difficulty relaxing emotionally around others. This score suggests that mistrust-related beliefs may strongly influence attachment patterns and interpersonal relationships.',
            ],
            'High' => [
                'label'   => 'High Schema Activation',
                'range'   => '33–40',
                'summary' => 'You may strongly expect others to hurt, betray, manipulate, or take advantage of you emotionally. Emotional closeness and vulnerability may feel unsafe or threatening most of the time. You may remain highly guarded, suspicious, or emotionally defensive even in supportive relationships. This score suggests that deep mistrust and fear of emotional harm may significantly impact emotional security, intimacy, and relationship stability.',
            ],
        ],
        'negativity' => [
            'Low' => [
                'label'   => 'Low Schema Activation',
                'range'   => '8–16',
                'summary' => 'You generally maintain a balanced or optimistic perspective when facing challenges or uncertainty. Difficult situations may feel stressful, but you are usually able to recognize positive possibilities and emotional resilience. Temporary setbacks are less likely to define your overall outlook on life. This score suggests healthy emotional balance and realistic optimism.',
            ],
            'Mild' => [
                'label'   => 'Mild Schema Activation',
                'range'   => '17–24',
                'summary' => 'You may occasionally focus more on risks, worries, or potential negative outcomes during stressful situations. Certain experiences may trigger overthinking, doubt, or emotional caution. However, you are still generally capable of recognizing hope, positive possibilities, and emotional stability. This score suggests mild tendencies toward worry or pessimistic thinking patterns.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Schema Activation',
                'range'   => '25–32',
                'summary' => 'Negative expectations, worry patterns, or fear of disappointment may significantly affect your mood and emotional well-being. You may often anticipate problems, focus on worst-case scenarios, or struggle to feel hopeful about the future. Positive experiences may feel temporary or emotionally less convincing than negative possibilities. This score suggests that pessimistic thinking may strongly influence emotional balance, motivation, and stress levels.',
            ],
            'High' => [
                'label'   => 'High Schema Activation',
                'range'   => '33–40',
                'summary' => 'You may strongly focus on danger, disappointment, failure, or worst-case outcomes in many areas of life. Hope, optimism, or emotional security may feel difficult to maintain consistently. Negative thinking patterns may create chronic worry, emotional exhaustion, or difficulty enjoying positive experiences. This score suggests that pessimism and negative expectation patterns may deeply affect emotional resilience, motivation, and overall well-being.',
            ],
        ],
        'punitiveness' => [
            'Low' => [
                'label'   => 'Low Schema Activation',
                'range'   => '8–16',
                'summary' => 'You generally view mistakes as normal parts of learning and personal growth. You are more likely to approach yourself and others with flexibility, understanding, and emotional balance after errors occur. Criticism or failure does not automatically lead to harsh judgment or punishment. This score suggests healthy emotional tolerance and self-compassion.',
            ],
            'Mild' => [
                'label'   => 'Mild Schema Activation',
                'range'   => '17–24',
                'summary' => 'You may occasionally become overly critical of yourself or others after mistakes or failures. Certain situations may trigger frustration, guilt, or rigid expectations about responsibility and consequences. However, these reactions are usually manageable and temporary. This score suggests mild tendencies toward self-criticism or strict standards regarding mistakes.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Schema Activation',
                'range'   => '25–32',
                'summary' => 'You may frequently judge yourself or others harshly when mistakes, weaknesses, or failures occur. Forgiveness, emotional flexibility, or acceptance may feel difficult during emotionally stressful situations. You may believe that people should face strong consequences for wrongdoing or imperfection. This score suggests that rigid judgment and punitive thinking may significantly affect emotional well-being and relationships.',
            ],
            'High' => [
                'label'   => 'High Schema Activation',
                'range'   => '33–40',
                'summary' => 'You may strongly believe that mistakes, weaknesses, or failures deserve harsh criticism or punishment. Compassion, forgiveness, or emotional flexibility may feel uncomfortable or undeserved. You may become extremely self-critical or emotionally harsh toward yourself and others after perceived failure or imperfection. This score suggests that punitive beliefs may deeply impact self-esteem, emotional regulation, stress levels, and interpersonal relationships.',
            ],
        ],
        'self_sacrifice' => [
            'Low' => [
                'label'   => 'Low Schema Activation',
                'range'   => '8–16',
                'summary' => 'You are generally able to balance caring for others while still respecting your own emotional and personal needs. Helping people does not usually require ignoring your well-being or boundaries. You can provide support without excessive guilt or emotional exhaustion. This score suggests healthy empathy combined with balanced self-care.',
            ],
            'Mild' => [
                'label'   => 'Mild Schema Activation',
                'range'   => '17–24',
                'summary' => 'You may occasionally prioritize others\' needs over your own, especially in emotionally important relationships. At times, you may feel guilty when focusing on yourself or setting boundaries. However, you are usually still capable of recognizing and protecting your own needs when necessary. This score suggests mild people-pleasing or self-sacrificing tendencies.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Schema Activation',
                'range'   => '25–32',
                'summary' => 'You may frequently place others\' emotional needs, comfort, or expectations above your own well-being. Saying no, setting boundaries, or prioritizing yourself may feel emotionally uncomfortable or selfish. Over time, this pattern may lead to emotional exhaustion, resentment, or neglect of personal needs. This score suggests that self-sacrificing behaviors may significantly affect emotional balance and relationships.',
            ],
            'High' => [
                'label'   => 'High Schema Activation',
                'range'   => '33–40',
                'summary' => 'You may strongly believe that your needs should come after the needs of others. Guilt, anxiety, or fear of disappointing people may make it extremely difficult to set healthy boundaries or focus on self-care. You may consistently ignore emotional exhaustion or personal needs in order to support others. This score suggests that excessive self-sacrifice may deeply impact emotional health, identity, and long-term well-being.',
            ],
        ],
        'social_isolation' => [
            'Low' => [
                'label'   => 'Low Schema Activation',
                'range'   => '8–16',
                'summary' => 'You generally feel connected to other people and capable of forming meaningful social relationships. Social environments usually feel comfortable and emotionally manageable for you. Even if you sometimes enjoy being alone, you do not strongly feel excluded or fundamentally different from others. This score suggests healthy social belonging and interpersonal connection.',
            ],
            'Mild' => [
                'label'   => 'Mild Schema Activation',
                'range'   => '17–24',
                'summary' => 'You may occasionally feel different, disconnected, or emotionally distant from people around you. Certain social situations may trigger insecurity, self-consciousness, or feelings of not fully fitting in. However, these feelings are usually temporary and do not consistently prevent social connection. This score suggests mild sensitivity to social belonging and interpersonal acceptance.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Schema Activation',
                'range'   => '25–32',
                'summary' => 'Feelings of loneliness, exclusion, or being fundamentally different from others may significantly affect social confidence and relationships. You may struggle to feel fully understood, accepted, or emotionally connected in groups or social environments. Social situations may create discomfort, emotional withdrawal, or fear of not belonging. This score suggests that social isolation beliefs may strongly influence relationships, confidence, and emotional well-being.',
            ],
            'High' => [
                'label'   => 'High Schema Activation',
                'range'   => '33–40',
                'summary' => 'You may strongly feel disconnected, excluded, or emotionally separate from other people most of the time. Forming close social relationships may feel difficult due to fear of rejection, emotional distance, or feeling fundamentally different from others. Even when surrounded by people, you may still experience deep loneliness or emotional isolation. This score suggests that social disconnection may significantly impact emotional health, relationships, and sense of belonging.',
            ],
        ],
        'subjugation' => [
            'Low' => [
                'label'   => 'Low Schema Activation',
                'range'   => '8–16',
                'summary' => 'You are generally able to express your opinions, needs, and boundaries in a healthy and balanced way. Disagreement or conflict does not usually feel overwhelmingly threatening or emotionally unsafe. You can make personal decisions without feeling excessive guilt or fear of upsetting others. This score suggests healthy assertiveness and emotional autonomy.',
            ],
            'Mild' => [
                'label'   => 'Mild Schema Activation',
                'range'   => '17–24',
                'summary' => 'You may occasionally suppress your needs, emotions, or opinions in order to avoid conflict or maintain harmony. Certain relationships or situations may make it difficult to speak openly or assert boundaries. However, you are usually still capable of expressing yourself when necessary. This score suggests mild tendencies toward people-pleasing or conflict avoidance.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Schema Activation',
                'range'   => '25–32',
                'summary' => 'You may frequently prioritize others\' expectations, opinions, or emotional comfort over your own needs and preferences. Fear of rejection, conflict, guilt, or disapproval may make self-expression emotionally difficult. Over time, suppressing emotions or personal needs may lead to resentment, frustration, or emotional exhaustion. This score suggests that subjugation patterns may significantly affect relationships, emotional well-being, and personal identity.',
            ],
            'High' => [
                'label'   => 'High Schema Activation',
                'range'   => '33–40',
                'summary' => 'You may strongly feel that your needs, feelings, or preferences are less important than those of others. Expressing disagreement, setting boundaries, or prioritizing yourself may create intense guilt, anxiety, or fear of rejection. You may consistently suppress emotions, avoid confrontation, or allow others to control important areas of your life. This score suggests that chronic self-suppression and fear of conflict may deeply affect emotional health, relationships, and self-worth.',
            ],
        ],
        'unrelenting_standards' => [
            'Low' => [
                'label'   => 'Low Schema Activation',
                'range'   => '8–16',
                'summary' => 'You generally maintain realistic expectations for yourself and others while still valuing growth and responsibility. Mistakes or imperfections do not strongly threaten your self-worth or emotional balance. You are usually capable of balancing achievement with rest, flexibility, and emotional well-being. This score suggests healthy motivation and balanced standards.',
            ],
            'Mild' => [
                'label'   => 'Mild Schema Activation',
                'range'   => '17–24',
                'summary' => 'You may occasionally place pressure on yourself to perform well, avoid mistakes, or meet high expectations. Certain situations may trigger perfectionistic thinking, self-criticism, or difficulty relaxing. However, you are usually still capable of maintaining emotional flexibility and balance. This score suggests mild perfectionistic tendencies or achievement pressure.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Schema Activation',
                'range'   => '25–32',
                'summary' => 'You may frequently feel driven to meet extremely high standards or constantly improve your performance. Mistakes, inefficiency, or imperfection may create significant stress, frustration, or self-criticism. Rest, relaxation, or emotional flexibility may feel undeserved unless productivity or achievement is maintained. This score suggests that perfectionism and hypercritical thinking may strongly affect emotional well-being, stress levels, and self-esteem.',
            ],
            'High' => [
                'label'   => 'High Schema Activation',
                'range'   => '33–40',
                'summary' => 'You may strongly believe that you must constantly achieve, perform perfectly, or avoid mistakes at all costs. Self-worth may become heavily dependent on productivity, success, or meeting unrealistic expectations. Even strong accomplishments may feel “not good enough,” leading to chronic pressure, emotional exhaustion, or burnout. This score suggests that perfectionism and relentless self-criticism may deeply impact emotional health, relationships, and overall quality of life.',
            ],
        ],
        'vulnerability' => [
            'Low' => [
                'label'   => 'Low Schema Activation',
                'range'   => '8–16',
                'summary' => 'You generally feel reasonably safe and capable of handling uncertainty or unexpected problems in life. While you recognize that risks exist, fear of danger or illness does not dominate your thoughts or emotional state. Stressful situations may feel challenging, but they usually remain manageable. This score suggests healthy emotional security and realistic awareness of risk.',
            ],
            'Mild' => [
                'label'   => 'Mild Schema Activation',
                'range'   => '17–24',
                'summary' => 'You may occasionally worry about illness, accidents, emotional loss, or unexpected disasters, especially during stressful periods. Certain situations may increase anxiety or create temporary feelings of insecurity. However, these fears are usually manageable and do not consistently interfere with daily life. This score suggests mild sensitivity to uncertainty, danger, or loss of control.',
            ],
            'Moderate' => [
                'label'   => 'Moderate Schema Activation',
                'range'   => '25–32',
                'summary' => 'Worries about danger, illness, catastrophe, or losing control may significantly affect emotional well-being and stress levels. You may frequently anticipate negative events or feel unsafe even in relatively normal situations. Anxiety about physical health, emotional security, or unexpected problems may lead to overthinking or avoidance behaviors. This score suggests that fear-based thinking may strongly influence emotional stability and daily functioning.',
            ],
            'High' => [
                'label'   => 'High Schema Activation',
                'range'   => '33–40',
                'summary' => 'You may strongly believe that serious danger, illness, catastrophe, or emotional disaster could happen at any moment. Feelings of safety and security may be difficult to maintain consistently, even in stable situations. Chronic anxiety, hypervigilance, or fear of losing control may significantly impact emotional well-being and everyday functioning. This score suggests that fear and vulnerability-related beliefs may deeply affect emotional resilience, stress regulation, and sense of safety.',
            ],
        ],
    ];
}
