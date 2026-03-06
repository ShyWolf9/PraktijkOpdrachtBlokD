<?php

namespace App\Http\Controllers;

use App\Models\GuessNumberScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuessNumberController extends Controller
{
    private const DIFFICULTIES = [
        'easy' => ['min' => 0, 'max' => 9, 'reward' => 5],
        'medium' => ['min' => 0, 'max' => 99, 'reward' => 20],
        'hard' => ['min' => 0, 'max' => 999, 'reward' => 100],
        'extreme' => ['min' => 0, 'max' => 9999, 'reward' => 1000],
        'impossible' => ['min' => 0, 'max' => 99999, 'reward' => 100000],
    ];

    public function index()
    {
        $game = session('guess_number_game');
        $lastDifficulty = session('guess_number_last_difficulty');
        $lastAttempts = session('guess_number_last_attempts');
        $leaderboard = GuessNumberScore::with('user')
            ->orderByDesc('reward')
            ->orderBy('attempts')
            ->latest()
            ->take(10)
            ->get();

        return view('games.guess-number', [
            'game' => $game,
            'lastDifficulty' => $lastDifficulty,
            'lastAttempts' => $lastAttempts,
            'leaderboard' => $leaderboard,
            'difficulties' => self::DIFFICULTIES,
        ]);
    }

    public function start(Request $request)
    {
        $validated = $request->validate([
            'difficulty' => 'required|in:easy,medium,hard,extreme,impossible',
        ]);

        $difficulty = $validated['difficulty'];
        $settings = self::DIFFICULTIES[$difficulty];

        session([
            'guess_number_game' => [
                'difficulty' => $difficulty,
                'min' => $settings['min'],
                'max' => $settings['max'],
                'target' => random_int($settings['min'], $settings['max']),
                'base_reward' => (float) $settings['reward'],
                'attempts' => 0,
                'hint' => null,
                'last_guess' => null,
            ],
            'guess_number_last_difficulty' => $difficulty,
        ]);

        session()->forget('guess_number_last_attempts');

        return redirect()->route('games.guess-number')
            ->with('success', 'New game started on ' . ucfirst($difficulty) . ' difficulty. Good luck!');
    }

    public function guess(Request $request)
    {
        $game = session('guess_number_game');

        if (!$game) {
            return redirect()->route('games.guess-number')
                ->with('error', 'Start a game first.');
        }

        $validated = $request->validate([
            'guess' => 'required|integer',
        ]);

        $guess = (int) $validated['guess'];

        if ($guess < $game['min'] || $guess > $game['max']) {
            return back()->with('error', 'Your guess must be between ' . $game['min'] . ' and ' . $game['max'] . '.');
        }

        $game['attempts']++;
        $game['last_guess'] = $guess;

        if ($guess === (int) $game['target']) {
            $reward = round($game['base_reward'] * pow(0.95, $game['attempts'] - 1), 2);

            $user = Auth::user();
            $user->balance = round($user->balance + $reward, 2);
            $user->save();

            GuessNumberScore::create([
                'user_id' => $user->id,
                'difficulty' => $game['difficulty'],
                'attempts' => $game['attempts'],
                'target' => $game['target'],
                'reward' => $reward,
            ]);

            session()->forget('guess_number_game');
            session(['guess_number_last_difficulty' => $game['difficulty']]);
            session(['guess_number_last_attempts' => $game['attempts']]);

            return redirect()->route('games.guess-number')->with(
                'success',
                'Correct! You guessed it in ' . $game['attempts'] . ' guess(es) and won €' . number_format($reward, 2) . '.'
            );
        }

        if ($guess < (int) $game['target']) {
            $game['hint'] = 'Higher';
        } else {
            $game['hint'] = 'Lower';
        }

        session(['guess_number_game' => $game]);

        $nextReward = round($game['base_reward'] * pow(0.95, $game['attempts']), 2);

        return redirect()->route('games.guess-number')
            ->with('error', 'Wrong guess. Hint: ' . $game['hint'] . '. Potential reward is now €' . number_format($nextReward, 2) . '.');
    }

    public function reset()
    {
        $game = session('guess_number_game');
        session()->forget('guess_number_game');

        if ($game && isset($game['difficulty'])) {
            session(['guess_number_last_difficulty' => $game['difficulty']]);
            session(['guess_number_last_attempts' => $game['attempts']]);
        }

        return redirect()->route('games.guess-number')
            ->with('success', 'Game reset. Choose a difficulty to start again.');
    }
}
