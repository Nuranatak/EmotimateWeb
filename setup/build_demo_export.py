#!/usr/bin/env python3
"""Generate database/emotimate_with_demo.sql for phpMyAdmin import."""

from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
OUT = ROOT / "database" / "emotimate_with_demo.sql"
SCHEMA = ROOT / "database" / "emotimate.sql"

ADMIN_HASH = "$2y$10$iA0Vu2c0YRT//asChW/E2.g4.aQFCksMhgCFjX/bCSovxsBxzovsK"
USER_HASH = "$2y$10$U.pmEB9JWfw.affZ2nPviuaaA7DIW6j/73fbEslYh5GGIA4KJaNBe"

TESTS = [
    ("Schema Tests", "Abandonment / Instability Schema Scale", "Assesses fears that close relationships will end and emotional support will not continue.", [
        "I worry that people who are important to me will leave me.",
        "I feel that I will eventually lose the interest of people I care about.",
        "I feel insecure about whether people I love will stay with me.",
        "When someone I care about is away, I fear they will not return.",
        "I need other people more than they need me.",
    ]),
    ("Schema Tests", "Mistrust / Abuse Schema Scale", "Assesses expectations that others may hurt, manipulate, or take advantage.", [
        "I expect people to take advantage of me if I am not careful.",
        "I am suspicious of other people's motives.",
        "I find it hard to trust people completely.",
        "I believe it is important to keep my guard up with most people.",
        "It is only a matter of time before someone betrays me.",
    ]),
    ("Depression and Anxiety Scales", "Depression Test (PHQ-9)", "Nine-item depression screening scale (Likert adaptation).", [
        "Little interest or pleasure in doing things.",
        "Feeling down, depressed, or hopeless.",
        "Trouble falling or staying asleep, or sleeping too much.",
        "Feeling tired or having little energy.",
        "Poor appetite or overeating.",
        "Feeling bad about yourself — or that you are a failure.",
        "Trouble concentrating on things.",
        "Moving or speaking slowly, or being fidgety/restless.",
        "Thoughts that you would be better off dead or hurting yourself.",
    ]),
    ("Depression and Anxiety Scales", "Anxiety Test (GAD-7)", "Seven-item generalized anxiety screening scale.", [
        "Feeling nervous, anxious, or on edge.",
        "Not being able to stop or control worrying.",
        "Worrying too much about different things.",
        "Trouble relaxing.",
        "Being so restless that it is hard to sit still.",
        "Becoming easily annoyed or irritable.",
        "Feeling afraid as if something awful might happen.",
    ]),
    ("Emotion and Mood Scales", "Emotional Awareness & Regulation Scale", "Measures emotional awareness and regulation.", [
        "I can usually identify what emotion I am feeling.",
        "I understand why I react emotionally in many situations.",
        "I can calm myself down when I feel upset.",
        "I express my feelings in healthy ways.",
        "I recover reasonably well after emotional stress.",
        "Strong emotions sometimes overwhelm me.",
        "I avoid talking about feelings even when it would help.",
        "I notice physical signs of stress early.",
    ]),
    ("Emotion and Mood Scales", "Personality Style Inventory", "Brief personality style self-report inventory.", [
        "I enjoy meeting new people and social gatherings.",
        "I prefer planning ahead rather than acting spontaneously.",
        "I stay calm under pressure most of the time.",
        "I appreciate art, ideas, and creative experiences.",
        "I go out of my way to help others.",
        "I pay close attention to details and organization.",
        "I am comfortable taking the lead in groups.",
        "I often reflect deeply before making decisions.",
    ]),
    ("Attention and Impulsivity Tests", "Attention & Focus Scale", "Assesses concentration and focus.", [
        "I can stay focused on tasks for a reasonable period.",
        "I complete projects I start.",
        "I am easily distracted by notifications or noise.",
        "I forget appointments or deadlines occasionally.",
        "I feel mentally organized when working.",
        "Long tasks feel exhausting to finish.",
    ]),
    ("Post-Traumatic Stress Disorder", "Trauma Re-Experiencing & Intrusive Thoughts Scale", "Screens intrusive memories and trauma-related stress.", [
        "Upsetting memories of stressful events come back unexpectedly.",
        "I have disturbing dreams related to difficult experiences.",
        "I feel upset when reminded of painful events.",
        "I avoid places or people that trigger difficult memories.",
        "I feel on guard or watchful in everyday situations.",
        "I am easily startled.",
    ]),
]

SAMPLE = [
    ("Abandonment / Instability Schema Scale", [4, 3, 4, 3, 4], 18, "Mild"),
    ("Depression Test (PHQ-9)", [1, 2, 1, 2, 1, 1, 2, 1, 1], 12, "Moderate"),
    ("Anxiety Test (GAD-7)", [2, 2, 2, 2, 1, 1, 2], 14, "Moderate"),
    ("Emotional Awareness & Regulation Scale", [4, 4, 3, 4, 3, 2, 2, 4], 26, "Moderate"),
    ("Personality Style Inventory", [2, 2, 3, 2, 3, 2, 2, 3], 17, "Medium"),
]


def esc(s: str) -> str:
    return s.replace("\\", "\\\\").replace("'", "''")


def main() -> None:
    lines = [
        "-- EMOTIMATE — full schema + demo data export",
        "-- Import in phpMyAdmin: database `emotimate`",
        "-- Charset: utf8mb4",
        "",
        "SET NAMES utf8mb4;",
        "SET CHARACTER SET utf8mb4;",
        "SET FOREIGN_KEY_CHECKS = 0;",
        "",
        "DROP TABLE IF EXISTS answers;",
        "DROP TABLE IF EXISTS results;",
        "DROP TABLE IF EXISTS questions;",
        "DROP TABLE IF EXISTS tests;",
        "DROP TABLE IF EXISTS test_categories;",
        "DROP TABLE IF EXISTS users;",
        "",
        "SET FOREIGN_KEY_CHECKS = 1;",
        "",
    ]

    schema = SCHEMA.read_text(encoding="utf-8")
    lines.append(schema.strip())
    lines.append("")

    lines.append("-- Demo users (admin123 / user123)")
    lines.append(
        f"INSERT INTO users (id, name, email, password, role) VALUES\n"
        f"(1, 'EMOTIMATE Administrator', 'admin@emotimate.com', '{ADMIN_HASH}', 'admin'),\n"
        f"(2, 'Sarah Mitchell', 'sarah.demo@emotimate.com', '{USER_HASH}', 'user');"
    )
    lines.append("")

    test_id = 0
    question_id = 0
    test_title_to_id = {}
    question_ids_by_test = {}

    for cat, title, desc, questions in TESTS:
        test_id += 1
        test_title_to_id[title] = test_id
        lines.append(
            f"INSERT INTO tests (id, category_id, title, description, instructions) "
            f"SELECT {test_id}, id, '{esc(title)}', '{esc(desc)}', NULL "
            f"FROM test_categories WHERE name = '{esc(cat)}' LIMIT 1;"
        )
        qids = []
        for q in questions:
            question_id += 1
            qids.append(question_id)
            lines.append(
                f"INSERT INTO questions (id, test_id, question_text) VALUES "
                f"({question_id}, {test_id}, '{esc(q)}');"
            )
        question_ids_by_test[title] = qids

    lines.append("")
    lines.append("-- Demo results for member (user id 2)")
    result_id = 0
    for title, answers, score, interp in SAMPLE:
        result_id += 1
        tid = test_title_to_id[title]
        lines.append(
            f"INSERT INTO results (id, user_id, test_id, score, interpretation) VALUES "
            f"({result_id}, 2, {tid}, {score}, '{esc(interp)}');"
        )
        for qid, val in zip(question_ids_by_test[title], answers):
            lines.append(
                f"INSERT INTO answers (user_id, question_id, value) VALUES (2, {qid}, {val});"
            )

    lines.append("")
    lines.append("-- Reset AUTO_INCREMENT after explicit IDs")
    lines.append("ALTER TABLE users AUTO_INCREMENT = 3;")
    lines.append("ALTER TABLE test_categories AUTO_INCREMENT = 6;")
    lines.append(f"ALTER TABLE tests AUTO_INCREMENT = {test_id + 1};")
    lines.append(f"ALTER TABLE questions AUTO_INCREMENT = {question_id + 1};")
    lines.append(f"ALTER TABLE results AUTO_INCREMENT = {result_id + 1};")
    lines.append("ALTER TABLE answers AUTO_INCREMENT = 1;")
    lines.append("")
    lines.append("SET FOREIGN_KEY_CHECKS = 1;")

    OUT.write_text("\n".join(lines) + "\n", encoding="utf-8")
    print(f"Wrote {OUT} ({len(lines)} lines)")


if __name__ == "__main__":
    main()
