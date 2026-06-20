<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuestionAnswerSeeder extends Seeder
{
    public function run(): void
    {
        $byEmail = fn (string $email) => User::where('email', $email)->first();
        $doctorFor = fn (string $email) => $byEmail($email)->doctor;

        $kovac = $doctorFor('elena.kovac@wellmate.test');
        $berg = $doctorFor('johan.berg@wellmate.test');
        $novakova = $doctorFor('marta.novakova@wellmate.test');

        $teen16 = $byEmail('teen16@wellmate.test');
        $teen15 = $byEmail('teen15@wellmate.test');
        $divorce41 = $byEmail('divorce41@wellmate.test');
        $divorce35 = $byEmail('divorce35@wellmate.test');
        $expecting = $byEmail('expecting@wellmate.test');

        $answered = [
            [
                'asker' => $teen16,
                'doctor' => $kovac,
                'daysAgo' => 18,
                'category' => 'mental-health',
                'tags' => ['anxiety', 'teens'],
                'title' => "I feel anxious every single morning before school and I don't even know why. Is something wrong with me?",
                'body' => "I feel anxious every single morning before school and I don't even know why. Is something wrong with me?",
                'answer' => "That knot-in-your-stomach feeling before school is one of the most common things I hear from teens, and it doesn't mean something is wrong with you. Anxiety often shows up as a learned stress response even without one clear cause. A few things help: naming the feeling out loud, slow breathing before you leave, and talking to a parent or school counselor if it's affecting your days. If this has gone on for more than a few weeks or is making school hard to face, it's worth a proper consultation so we can look at it together.",
            ],
            [
                'asker' => $teen15,
                'doctor' => $kovac,
                'daysAgo' => 16,
                'category' => 'mental-health',
                'tags' => ['body-image', 'puberty'],
                'title' => 'All my friends look so much more developed than me and it makes me feel really insecure. Is this just me?',
                'body' => 'All my friends look so much more developed than me and it makes me feel really insecure. Is this just me?',
                'answer' => "This is incredibly common — puberty timing varies enormously between individuals, sometimes by two or three years, and it has nothing to do with anything being wrong with you. Comparing yourself to friends feels natural but is rarely fair, since everyone's timeline is different. If it's affecting your confidence a lot, talking to a parent or school nurse can help. And if there's no development starting at all by around 15–16, that's worth checking with a doctor just to be thorough.",
            ],
            [
                'asker' => $divorce41,
                'doctor' => $berg,
                'daysAgo' => 14,
                'category' => 'mental-health',
                'tags' => ['divorce', 'grief'],
                'title' => 'My divorce was finalized last month and I still cry randomly during the day. How long does this usually last?',
                'body' => 'My divorce was finalized last month and I still cry randomly during the day. How long does this usually last?',
                'answer' => "There isn't a fixed timeline, and that's honestly the hardest part to accept. Divorce grief can resemble bereavement — sudden waves, good days followed by bad ones, sometimes even a year or more later. What matters more than \"how long\" is whether the waves are slowly spacing further apart over time. If they're not easing at all after several months, or you feel stuck rather than slowly moving through it, talking with a therapist can help you process it instead of just waiting it out.",
            ],
            [
                'asker' => $divorce35,
                'doctor' => $berg,
                'daysAgo' => 11,
                'category' => 'mental-health',
                'tags' => ['divorce', 'relief'],
                'title' => "I'm going through a separation and honestly I feel relieved more than sad. Is that a terrible thing to feel?",
                'body' => "I'm going through a separation and honestly I feel relieved more than sad. Is that a terrible thing to feel?",
                'answer' => "Not at all — relief is one of the most under-talked-about emotions in separation, and feeling it doesn't make you a bad person. Often it means the relationship had been quietly draining you for a long time before the decision was made. Guilt tends to creep in because we expect grief to look one specific way. It's worth giving yourself permission to feel whatever comes up, including relief, without judging it. If the guilt becomes heavier than the relief, that contrast is worth exploring with a therapist.",
            ],
            [
                'asker' => $expecting,
                'doctor' => $novakova,
                'daysAgo' => 60,
                'category' => 'pregnancy',
                'tags' => ['pregnancy', 'caffeine'],
                'title' => "Is it bad if I still drink one cup of coffee a day? I can't function without it.",
                'body' => "I'm 12 weeks pregnant. Is it bad if I still drink one cup of coffee a day? I can't function without it.",
                'answer' => "One regular cup is generally considered safe — most guidelines put the limit around 200mg of caffeine daily, roughly one 250ml cup of filter coffee. Where it adds up is hidden caffeine from tea, chocolate, or cola on top of that. So your one cup is very likely fine; just keep an eye on the extras throughout the day.",
            ],
            [
                'asker' => $expecting,
                'doctor' => $novakova,
                'daysAgo' => 45,
                'category' => 'pregnancy',
                'tags' => ['pregnancy', 'fetal-movement'],
                'title' => 'Is it normal to feel little flutters at 16 weeks, or is that too early to feel the baby move?',
                'body' => "I'm 16 weeks pregnant. Is it normal to feel little flutters, or is that too early to feel the baby move?",
                'answer' => 'Sixteen weeks is right on the early edge for this — most first-time mothers feel it closer to 18–20 weeks, though it can come earlier, especially in a second pregnancy. Those flutters are very likely your baby moving; some describe it as bubbles or a light tapping. If you\'re not feeling anything by 22–24 weeks, mention it at your next appointment just to confirm positioning.',
            ],
            [
                'asker' => $expecting,
                'doctor' => $novakova,
                'daysAgo' => 30,
                'category' => 'pregnancy',
                'tags' => ['pregnancy', 'safety'],
                'title' => "Can I still go on rollercoasters? My friend's birthday is at an amusement park and I don't want to feel left out.",
                'body' => "I'm 20 weeks pregnant. Can I still go on rollercoasters? My friend's birthday is at an amusement park and I don't want to feel left out.",
                'answer' => "Most OB-GYNs would advise skipping the big rides — sudden jolts and rapid stops carry a small but real risk of placental issues, especially as pregnancy progresses. The good news is you can still go and enjoy the day, just from the sidelines or on gentler rides. Nobody at the party should think less of you for sitting that one out.",
            ],
            [
                'asker' => $expecting,
                'doctor' => $novakova,
                'daysAgo' => 20,
                'category' => 'pregnancy',
                'tags' => ['pregnancy', 'nutrition'],
                'title' => 'Can I eat sushi during pregnancy or is that a strict no?',
                'body' => "I'm 24 weeks pregnant. Can I eat sushi during pregnancy or is that a strict no?",
                'answer' => "It depends on the type. Vegetable or fully-cooked sushi — tempura rolls, cooked shrimp — is completely fine. Raw fish is where caution comes in, mainly due to bacteria and parasite risk, not because fish itself is harmful. If you're craving raw sushi, choose a reputable restaurant with high turnover and ask if the fish is sushi-grade and was previously flash-frozen — that significantly reduces the risk.",
            ],
            [
                'asker' => $expecting,
                'doctor' => $novakova,
                'daysAgo' => 12,
                'category' => 'pregnancy',
                'tags' => ['pregnancy', 'sleep'],
                'title' => 'Is it true I have to sleep only on my left side now? I keep waking up on my back and panicking.',
                'body' => "I'm 27 weeks pregnant. Is it true I have to sleep only on my left side now? I keep waking up on my back and panicking.",
                'answer' => "Left-side sleeping is recommended because it's best for blood flow to the baby, but waking up on your back occasionally isn't dangerous — it just means your body shifted while you were relaxed, which is completely normal. The advice is about your general habit and falling-asleep position, not a strict rule to panic over every time you wake up.",
            ],
            [
                'asker' => $expecting,
                'doctor' => $novakova,
                'daysAgo' => 5,
                'category' => 'pregnancy',
                'tags' => ['pregnancy', 'hair-dye'],
                'title' => "Can I still dye my hair? I've read so many conflicting things online.",
                'body' => "I'm 30 weeks pregnant. Can I still dye my hair? I've read so many conflicting things online.",
                'answer' => "You can — the conflicting advice usually comes from older, more cautious guidance. Modern research shows very little hair dye is absorbed through the scalp, so the risk is considered minimal, especially after the first trimester. If you want extra peace of mind, ask your salon for ammonia-free dye and make sure the room is well-ventilated.",
            ],
        ];

        foreach ($answered as $q) {
            $question = Question::create([
                'user_id' => $q['asker']->id,
                'title' => $q['title'],
                'body' => $q['body'],
                'category' => $q['category'],
                'status' => 'answered',
                'is_anonymous' => true,
                'created_at' => now()->subDays($q['daysAgo']),
                'updated_at' => now()->subDays($q['daysAgo']),
            ]);

            $question->tags()->sync(
                collect($q['tags'])->map(fn (string $name) => Tag::findOrCreateByName($name)->id)
            );

            Answer::create([
                'question_id' => $question->id,
                'doctor_id' => $q['doctor']->id,
                'body' => $q['answer'],
                'is_accepted' => true,
                'created_at' => now()->subDays($q['daysAgo'])->addHours(6),
                'updated_at' => now()->subDays($q['daysAgo'])->addHours(6),
            ]);
        }

        // Fresh, unanswered questions — left pending on purpose so the
        // "hidden until a doctor answers" flow and the doctor dashboard's
        // pending queue can be demoed live.
        $pending = [
            [
                'asker' => $divorce35,
                'category' => 'mental-health',
                'tags' => ['divorce', 'isolation'],
                'title' => "I've started avoiding my friends since my separation, is that normal?",
                'body' => "Since my separation a few weeks ago I've been turning down every invite and just staying home. I've started avoiding my friends since my separation — is that normal, or am I isolating in an unhealthy way?",
                'daysAgo' => 1,
            ],
            [
                'asker' => $expecting,
                'category' => 'pregnancy',
                'tags' => ['pregnancy', 'third-trimester'],
                'title' => 'Is occasional dizziness in the third trimester something to worry about?',
                'body' => "I'm 32 weeks pregnant and have had a few short dizzy spells when I stand up quickly. Is occasional dizziness in the third trimester something to worry about, or is it just normal pregnancy stuff?",
                'daysAgo' => 0,
            ],
        ];

        foreach ($pending as $q) {
            $question = Question::create([
                'user_id' => $q['asker']->id,
                'title' => $q['title'],
                'body' => $q['body'],
                'category' => $q['category'],
                'status' => 'pending',
                'is_anonymous' => true,
                'created_at' => now()->subDays($q['daysAgo']),
                'updated_at' => now()->subDays($q['daysAgo']),
            ]);

            $question->tags()->sync(
                collect($q['tags'])->map(fn (string $name) => Tag::findOrCreateByName($name)->id)
            );
        }
    }
}
