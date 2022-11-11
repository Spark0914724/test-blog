<?php

declare(strict_types=1);

namespace App\Controller;

use PDO;
use Smarty\Smarty;

final class SeederController
{
    public function __construct(
        private PDO $pdo,
        private Smarty $smarty,
        private array $config
    ) {}

    public function run(): void
    {
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        $this->pdo->exec('TRUNCATE TABLE article_categories');
        $this->pdo->exec('TRUNCATE TABLE articles');
        $this->pdo->exec('TRUNCATE TABLE categories');
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

        $this->seedCategories();
        $this->seedArticles();

        header('Location: index.php?page=home', true, 302);
        exit;
    }

    private function seedCategories(): void
    {
        $categories = [
            ['Technology', 'Articles about software, programming, and tech trends.'],
            ['Travel', 'Travel guides, tips, and destination stories.'],
            ['Lifestyle', 'Health, wellness, and everyday life.'],
        ];

        $stmt = $this->pdo->prepare('INSERT INTO categories (title, description) VALUES (?, ?)');
        foreach ($categories as $c) {
            $stmt->execute([$c[0], $c[1]]);
        }
    }

    private function seedArticles(): void
    {
        $categoryIds = $this->pdo->query('SELECT id FROM categories ORDER BY id')->fetchAll(PDO::FETCH_COLUMN);
        if (empty($categoryIds)) {
            return;
        }

        $articles = [
            // Technology
            [
                '/assets/img/tech1.jpg',
                'Getting Started with PHP 8',
                'A step‑by‑step introduction to the most important language features added in PHP 8 and how they affect everyday backend development.',
                "PHP 8 introduced a number of language features – such as union types, attributes and the JIT – that change how we design and optimise applications.\n\nIn this article we start from a blank project and gradually refactor a small piece of code to take advantage of the new syntax. Along the way we discuss which features are worth adopting immediately, which ones are nice to have, and which are mostly syntactic sugar.\n\nThe goal is not to list every change in the release notes, but to give you a realistic picture of how PHP 8 feels in a real codebase so that upgrading your own project becomes less intimidating.",
                [0],
            ],
            [
                '/assets/img/tech2.jpg',
                'Understanding MySQL Indexes',
                'A practical guide to how B‑tree indexes work in MySQL and how to design them so that your queries stay fast as your tables grow.',
                "Indexes are one of the most powerful tools you have to improve database performance, but they are also one of the most misunderstood.\n\nWe look at how MySQL uses B‑tree structures internally, what a “left‑most prefix” actually means, and how composite indexes change the way the optimiser builds query plans. You will see examples of slow queries, learn how to inspect their EXPLAIN output, and then design targeted indexes to fix the problem.\n\nBy the end you will have a small checklist you can apply to every table to avoid both over‑indexing and missing critical indexes.",
                [0],
            ],
            [
                '/assets/img/tech3.jpg',
                'Smarty Templates in Practice',
                'Using Smarty to keep PHP logic out of your HTML while still keeping templates readable for designers and non‑developers.',
                "Template engines are often introduced with trivial examples, but things get more interesting once a project has dozens of layouts and partials.\n\nThis article walks through a small blog implementation built with Smarty. We look at how to organise templates into layouts and sub‑templates, how to pass data from controllers, and how to keep conditional logic in PHP instead of the view.\n\nWe also cover security‑related topics such as auto‑escaping, escaping raw HTML safely, and strategies for sharing common UI components across multiple pages without duplicating markup.",
                [0],
            ],
            [
                '/assets/img/tech4.jpg',
                'Docker for PHP Developers',
                'Building a simple Docker setup for PHP, Nginx and MySQL so that your whole team can share the same local environment.',
                "Running a PHP project directly on your host machine works for side projects, but it quickly becomes painful when several developers need to share the same stack.\n\nHere we assemble a small Docker Compose configuration step by step. We start with a PHP‑FPM container, add Nginx for static assets, and finally connect a MySQL service. You will learn how to mount your source code, configure xdebug, and persist database data between restarts.\n\nThe final result is a reproducible environment that closely mirrors production while staying easy to run with a single command.",
                [0, 1],
            ],
            [
                '/assets/img/tech1.jpg',
                'Clean Architecture in PHP',
                'An overview of layering a PHP codebase into domain, application and infrastructure so that business rules stay independent from frameworks.',
                "When a project starts small it is tempting to put everything into controllers and models. Over time this makes it harder to change business rules without touching multiple layers.\n\nUsing a simple blogging feature as an example, we split the code into domain services, application services and infrastructure adapters. Each layer has clear responsibilities, and dependencies only point inward. We then show how this structure still works fine with plain PHP and does not require a heavy framework.\n\nIf you have ever struggled to add a feature without breaking unrelated parts of the system, this article gives you a vocabulary and a set of patterns to untangle things.",
                [0, 2],
            ],
            [
                '/assets/img/tech2.jpg',
                'Working with PDO Safely',
                'Practical patterns for using PDO with prepared statements, transactions and proper error handling in everyday CRUD code.',
                "PDO is a small but powerful abstraction over different SQL drivers. Used correctly it dramatically reduces the risk of SQL injection bugs.\n\nWe start with the basics – connection setup and prepared statements – and then move on to transactions, consistent error handling and small helper functions to keep repository classes readable. Each example includes both the PHP code and the resulting SQL so you clearly see what is being executed.\n\nThe article closes with a short checklist you can apply to existing code to harden it without rewriting everything from scratch.",
                [0],
            ],
            [
                '/assets/img/tech3.jpg',
                'Simple Pagination Logic',
                'How limit/offset pagination works in SQL and how to implement it cleanly in PHP controllers and repositories.',
                "Most applications eventually need to show long lists of items. Pagination is not complicated, but small mistakes can lead to confusing UX or poor performance.\n\nWe build a small pagination helper around a single repository method: it accepts the current page and page size, and returns both the records and total count. On the front‑end we keep URLs clean by using query parameters only for state that actually matters.\n\nYou will see how this pattern maps perfectly to Smarty templates and how to keep the logic generic so it can be re‑used for any listing page, not just articles.",
                [0, 1],
            ],
            [
                '/assets/img/tech4.jpg',
                'Caching Strategy Basics',
                'Deciding what to cache, where to cache it, and how to invalidate caches in a small but realistic PHP application.',
                "Caching can make a slow page feel instant, but a badly designed cache layer can be harder to debug than the original performance problem.\n\nIn this article we distinguish between data that rarely changes (configuration, reference data) and volatile data (user‑specific pages, dashboards). We then look at three simple caching layers: in‑process caching with static variables, file‑based caching, and using an external store such as Redis.\n\nEach section includes concrete PHP examples and discusses trade‑offs so you can pick the smallest solution that solves your current problem.",
                [0, 2],
            ],

            // Travel
            [
                '/assets/img/travel1.jpg',
                'Best Beaches in Europe',
                'A curated shortlist of European beach destinations that combine beautiful scenery with easy access and reasonable budgets.',
                "Europe has thousands of kilometres of coastline and it can be hard to decide where to spend a precious summer holiday.\n\nWe look at five destinations – from the Algarve in Portugal to small islands in Greece – and compare them on climate, crowds, prices and accessibility. Each section includes tips on the best time to visit, how to get around without a car, and a couple of lesser‑known spots that are still quiet.\n\nThe aim is not to produce a definitive ranking, but to give you enough context to quickly choose a place that matches your travel style.",
                [1],
            ],
            [
                '/assets/img/travel2.jpg',
                'Weekend in Paris',
                'A focused two‑day itinerary that balances the must‑see highlights of Paris with slower moments in neighbourhood cafés.',
                "Many people only have a weekend to experience Paris. Trying to see everything guarantees that you enjoy nothing.\n\nThis guide proposes a realistic schedule for two full days: one centred around the classic sights along the Seine, and one that explores smaller streets, bookshops and parks. We include suggestions for bakeries and bistros that feel local without being intimidating for first‑time visitors.\n\nYou can follow the itinerary exactly or use it as a template for future city breaks in other European capitals.",
                [1],
            ],
            [
                '/assets/img/travel3.jpg',
                'Hidden Gems in Italy',
                'Smaller Italian towns and coastal villages that are easy to reach but often skipped in favour of the big three: Rome, Florence and Venice.',
                "Italy rewards travellers who are willing to step one train stop beyond the usual tourist circuit.\n\nIn this article we highlight a handful of places that combine historic centres with relaxed atmospheres – think harbour towns in Liguria, hilltop villages in Umbria, and lesser‑known islands off the coast of Sicily. Each mini‑guide includes how to get there by public transport and one or two simple walks you can do without a car.\n\nIf you enjoy Italy but dislike crowds, this list will give you a few ideas for your next trip.",
                [1],
            ],
            [
                '/assets/img/travel4.jpg',
                'Traveling by Train',
                'Why choosing trains over planes for medium‑distance trips can make travel more comfortable, productive and environmentally friendly.',
                "Train journeys are often perceived as slower, yet for trips of a few hundred kilometres they can beat flying door‑to‑door.\n\nWe compare actual travel days on three routes, including the time spent getting to and from airports, security queues and delays. You will see how working or reading on the train changes how tired you feel at your destination. The article also covers practical tips: ticket booking tools, how to handle connections, and what to pack so you are comfortable on board.\n\nBy the end you may not switch all flights to trains, but you will know when it makes sense to do so.",
                [1, 2],
            ],
            [
                '/assets/img/travel1.jpg',
                'Packing Light for Long Trips',
                'Strategies for travelling with only a carry‑on suitcase, even on multi‑week journeys that span different climates.',
                "Packing light is less about owning special gear and more about making deliberate choices.\n\nWe start by defining a realistic packing list for a three‑week trip that includes both city days and short hikes. You will learn how to build outfits around a limited colour palette, how to choose fabrics that dry quickly, and which items are rarely worth bringing. We also talk about laundry on the road and how to keep your bag organised.\n\nTravelling with less weight does not only save baggage fees – it also gives you more freedom to change plans spontaneously.",
                [1],
            ],
            [
                '/assets/img/travel2.jpg',
                'Staying Safe While Abroad',
                'A calm, practical checklist for staying safe in unfamiliar cities without becoming anxious or overly suspicious.',
                "Most trips are uneventful, but a few simple habits can greatly reduce your exposure to risk when something does go wrong.\n\nThis article covers topics such as carrying documents, using ATMs, backing up important information and recognising common scams. We also touch on digital security: protecting your accounts when using public Wi‑Fi and what to do if your phone is lost or stolen.\n\nThe goal is to leave you feeling prepared but not paranoid so you can focus on actually enjoying your time away from home.",
                [0, 1],
            ],
            // Lifestyle
            [
                '/assets/img/life1.jpg',
                'Morning Routine Tips',
                'Small, realistic adjustments to your first hour of the day that make the rest of the day feel calmer and more intentional.',
                "Perfect routines on social media often assume unlimited time and motivation. Real mornings are messier.\n\nWe look at a handful of habits that work even on busy weekdays: preparing the evening before, starting the day without immediately checking notifications, and adding a tiny movement or reflection ritual. Each suggestion comes with concrete examples that take five minutes or less.\n\nYou do not need to adopt all of them – even picking one or two can noticeably change how your day unfolds.",
                [2],
            ],
            [
                '/assets/img/life2.jpg',
                'Minimalism at Home',
                'A gentle introduction to decluttering that focuses on creating breathing room rather than counting how many items you own.',
                "Decluttering is often framed as a strict challenge, but for most people it works better as a series of small experiments.\n\nWe start with one drawer and one shelf, learning to separate belongings into keep, donate and recycle piles. The article discusses emotional attachment to objects, how to handle gifts, and why it is okay to keep a few purely sentimental items.\n\nBy the end you will have a simple framework you can repeat room by room without burning out after a single weekend.",
                [2],
            ],
            [
                '/assets/img/life1.jpg',
                'Healthy Remote Work Habits',
                'Practical ideas for staying active, focused and socially connected when working from home for long periods.',
                "Remote work offers flexibility, but without boundaries days can easily blur into one another.\n\nHere we design a lightweight daily structure that includes movement breaks, clear start‑ and end‑of‑day rituals, and ways to replace casual office conversations. We look at both the physical workspace – light, posture, screen setup – and the social side: how to communicate availability and avoid meeting overload.\n\nThe goal is a routine that feels sustainable for months, not just a productivity sprint for a single week.",
                [1, 2],
            ],
            [
                '/assets/img/life2.jpg',
                'Building a Reading Habit',
                'Techniques for finishing more books without turning reading into yet another task on your to‑do list.',
                "Many people want to read more but feel they never have the time. The trick is to shrink the commitment.\n\nWe discuss setting tiny daily goals, choosing the right difficulty level for different times of day, and always keeping a book within reach – whether physical or digital. There is also a short section on tracking reading in a way that motivates you without becoming a chore in itself.\n\nOver a few months these small changes compound into dozens of finished books without any sense of pressure.",
                [2],
            ],
            [
                '/assets/img/life1.jpg',
                'Digital Detox Weekend',
                'A simple weekend experiment for reducing screen time and noticing how constant notifications affect your attention.',
                "You do not need to delete every app to feel the benefits of a digital detox. Starting with a weekend is enough.\n\nThis article proposes a low‑friction plan: deciding upfront which apps you will avoid, setting up temporary blockers, and preparing offline alternatives such as downloaded maps, playlists and reading material. We also suggest a short reflection exercise on Sunday evening to capture what you noticed.\n\nThe intention is not to reject technology entirely, but to return to it more deliberately the following week.",
                [2],
            ],
            [
                '/assets/img/life2.jpg',
                'Simple Meal Prep Ideas',
                'A handful of base recipes that can be cooked once and combined into different meals throughout a busy week.',
                "Cooking at home every evening is hard when you also juggle work, family and other responsibilities.\n\nWe show how to prepare a few versatile components – roasted vegetables, a grain, a protein and a sauce – in about ninety minutes on the weekend. The rest of the article demonstrates how to mix and match them into different lunches and dinners so that the food stays interesting without requiring complex recipes.\n\nThis approach reduces decision fatigue and makes it much easier to eat well when you are tired or short on time.",
                [0, 2],
            ],
        ];

        $ins = $this->pdo->prepare(
            'INSERT INTO articles (image, title, description, text, views, published_at) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $link = $this->pdo->prepare('INSERT INTO article_categories (article_id, category_id) VALUES (?, ?)');

        $baseDate = time() - 86400 * 30;
        foreach ($articles as $i => $a) {
            $published = date('Y-m-d H:i:s', $baseDate + $i * 86400 * 2);
            $ins->execute([$a[0], $a[1], $a[2], $a[3], rand(10, 500), $published]);
            $articleId = (int) $this->pdo->lastInsertId();
            foreach ($a[4] as $catIndex) {
                $link->execute([$articleId, $categoryIds[$catIndex]]);
            }
        }
    }
}
