<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $doctorFor = fn (string $email) => User::where('email', $email)->first()->doctor;

        $kovac = $doctorFor('elena.kovac@wellmate.test');
        $berg = $doctorFor('johan.berg@wellmate.test');
        $novakova = $doctorFor('marta.novakova@wellmate.test');

        $articles = [
            [
                'doctor' => $kovac,
                'daysAgo' => 28,
                'category' => 'mental-health',
                'cover_image' => 'https://picsum.photos/seed/teen-anxiety/800/500',
                'title' => "Why Teen Anxiety Isn't Just a Phase",
                'body' => "Anxiety among teenagers has climbed sharply over the past decade, and it's tempting to write it off as \"just a phase\" every generation goes through. But a few things have genuinely changed: relentless academic pressure that starts earlier each year, constant social comparison fueled by social media, and sleep patterns disrupted by screens late into the night. Layered together, these create a baseline of stress that didn't exist for previous generations in the same way.\n\nIt helps to be clear about what anxiety actually is: a normal stress response that has become oversensitive, not a character flaw or a sign of weakness. Most teens I see are anxious about ordinary things — exams, friendships, the future — but their nervous system has learned to react as if every one of those things is an emergency.\n\nThe good news is that this is highly treatable, often without medication, through small structural changes: consistent sleep, naming feelings instead of suppressing them, and breaking the cycle of late-night scrolling. But there are signs that mean it's time to bring in a professional rather than just riding it out: anxiety that stops a teen from going to school, panic attacks, or sleep loss that's gone on for weeks. If you're a parent and you're not sure whether what you're seeing is normal stress or something more, that uncertainty itself is reason enough to ask.",
            ],
            [
                'doctor' => $novakova,
                'daysAgo' => 25,
                'category' => 'pregnancy',
                'cover_image' => 'https://picsum.photos/seed/pregnancy-trimester/800/500',
                'title' => "Your Body at Every Trimester: What's Actually Normal",
                'body' => "Pregnancy is often described as nine months, but it really feels like three very different experiences stacked on top of each other, and a lot of unnecessary worry comes from not knowing which symptoms belong to which stage.\n\nIn the first trimester, fatigue, nausea, and breast tenderness dominate — driven by a sharp rise in hormones as the placenta establishes itself. Spotting can happen and isn't automatically alarming, but heavy bleeding or severe cramping warrants a same-day call to your provider.\n\nThe second trimester is, for most people, the most comfortable stretch: energy returns, nausea fades, and you'll likely feel the first fetal movements somewhere between 18 and 22 weeks. Round ligament pain — a sharp pull on one side of the lower belly — is extremely common as the uterus grows and isn't a sign of a problem on its own.\n\nThe third trimester brings some discomfort back: back pain, swelling, shortness of breath as space gets tight, and Braxton Hicks contractions that practice for labor without being labor. What's NOT normal at any stage and deserves a same-day call: severe headaches with vision changes, sudden swelling in the face or hands, a noticeable drop in fetal movement, or heavy bleeding. Knowing that distinction — ordinary discomfort versus a genuine warning sign — is most of what takes the anxiety out of pregnancy.",
            ],
            [
                'doctor' => $berg,
                'daysAgo' => 20,
                'category' => 'mental-health',
                'cover_image' => 'https://picsum.photos/seed/divorce-recovery/800/500',
                'title' => "Rebuilding After Divorce: A Psychologist's Guide to the First Year",
                'body' => "The first year after a divorce rarely moves in a straight line, but it does tend to move through recognizable stages, and knowing that can make the unpredictability feel less frightening.\n\nEarly on, there's often a strange mix of relief and shock — even when the divorce was the right decision, the mind is adjusting to the sudden absence of a structure it had built around for years. This is usually followed by a harder middle stretch, where the initial adrenaline fades and the real adjustment — financial, social, sometimes parenting logistics — sets in. It's common to feel like you're doing worse months in than you were in the first weeks; that's not a step backward, it's simply where the real work starts.\n\nWhat helps most during this stretch isn't willpower, it's structure: a steady daily routine, even a small one, gives you something predictable to hold onto. Rebuilding a support network deliberately — not waiting for people to check in, but reaching out yourself — matters more than people expect. And self-compassion isn't a soft add-on; treating yourself harshly during this period is one of the most common things that prolongs recovery rather than speeding it up.\n\nBy month nine to twelve, most people start to notice longer stretches of feeling like themselves again. If instead you feel stuck in the same place you were at month two or three, that's the marker that this might benefit from professional support rather than more time alone.",
            ],
            [
                'doctor' => $novakova,
                'daysAgo' => 15,
                'category' => 'pregnancy',
                'cover_image' => 'https://picsum.photos/seed/pregnancy-myths/800/500',
                'title' => 'Common Pregnancy Myths, Busted',
                'body' => "Almost every pregnant patient I see arrives with a list of \"can I still...\" questions, usually sourced from a worried relative or a late-night search that mixed outdated advice with current guidance. A few of the most common ones, sorted out:\n\nSushi: fully-cooked rolls are always fine. Raw fish carries a small bacteria/parasite risk, not a toxicity risk — choosing a high-turnover restaurant and asking if the fish was previously flash-frozen meaningfully lowers that risk if you do want it.\n\nCaffeine: one regular cup of coffee a day, around 200mg, is considered safe by most guidelines. The real risk is stacking tea, chocolate, and soda on top without noticing the total.\n\nHair dye: modern research shows minimal absorption through the scalp, so this is now considered low-risk, especially past the first trimester — ammonia-free dye and a well-ventilated room are reasonable extra precautions, not requirements.\n\nSleeping position: left-side sleeping is the recommendation for optimal blood flow, but waking up on your back isn't dangerous — it reflects what your body did while relaxed, not a habit to panic over.\n\nThe common thread: pregnancy guidance has gotten more nuanced over the last decade, but a lot of the older, stricter advice is still circulating online. When in doubt, it's always reasonable to ask your own OB-GYN directly rather than rely on a blanket internet rule that may not reflect current evidence.",
            ],
            [
                'doctor' => $kovac,
                'daysAgo' => 8,
                'category' => 'mental-health',
                'cover_image' => 'https://picsum.photos/seed/teen-body-image/800/500',
                'title' => 'Supporting a Teenager Through Body Image Struggles',
                'body' => "For most teenagers, puberty doesn't arrive on a tidy schedule — it can start two or three years earlier in one friend than another, and that gap is exactly where body image struggles take root. When a teenager looks around a classroom and sees peers who look years more \"developed,\" the comparison feels brutally direct, even though the timing difference is completely normal biology.\n\nAs a parent, the instinct is often to reassure with facts — \"everyone develops differently\" — and while that's true, it rarely lands the way we hope, because the teenager isn't looking for information, they're looking for someone to take the feeling seriously. Language that works better starts with acknowledging the feeling first — \"that sounds really hard to sit with every day\" — before gently introducing the bigger picture.\n\nIt's also worth normalizing that this isn't only a topic for girls — body image pressure shows up for teenage boys too, just often more quietly. What you're watching for as a parent is whether the comparison stays at the level of occasional insecurity, or tips into avoidance: skipping gym class, refusing to be seen in certain clothes, or withdrawing socially. That shift is the signal that reassurance at home may not be enough on its own, and a conversation with a pediatrician or adolescent specialist is a reasonable next step — not because something is wrong, but because extra support never hurts.",
            ],
        ];

        foreach ($articles as $a) {
            Article::create([
                'doctor_id' => $a['doctor']->id,
                'title' => $a['title'],
                'slug' => Article::generateUniqueSlug($a['title']),
                'body' => $a['body'],
                'category' => $a['category'],
                'cover_image' => $a['cover_image'],
                'is_published' => true,
                'created_at' => now()->subDays($a['daysAgo']),
                'updated_at' => now()->subDays($a['daysAgo']),
            ]);
        }
    }
}
