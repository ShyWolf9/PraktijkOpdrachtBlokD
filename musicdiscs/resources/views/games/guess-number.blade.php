@extends('layout.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Guess The Number</h4>
                </div>
                <div class="card-body">
                    <p class="mb-3">
                        Choose a difficulty, then guess the secret number. If you're correct, you earn money.
                        Each wrong guess lowers the prize by 5%.
                    </p>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Difficulty</th>
                                    <th>Range</th>
                                    <th>First Guess Reward</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($difficulties as $name => $settings)
                                    <tr>
                                        <td class="text-capitalize">{{ $name }}</td>
                                        <td>{{ $settings['min'] }} - {{ $settings['max'] }}</td>
                                        <td>€{{ number_format($settings['reward'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <form action="{{ route('games.guess-number.start') }}" method="POST" class="row g-2 mb-3">
                        @csrf
                        <div class="col-md-8">
                            <label for="difficulty" class="form-label">Difficulty</label>
                            <select id="difficulty" name="difficulty" class="form-select" required>
                                <option value="">Select difficulty</option>
                                @foreach($difficulties as $name => $settings)
                                    <option value="{{ $name }}" @if(($game['difficulty'] ?? null) === $name) selected @endif>
                                        {{ ucfirst($name) }} ({{ $settings['min'] }} - {{ $settings['max'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Start New Game</button>
                        </div>
                    </form>

                    @if(!$game && !empty($lastDifficulty))
                        @if(!empty($lastAttempts))
                            <div class="alert alert-secondary">
                                <strong>Last round guesses:</strong> {{ $lastAttempts }}
                            </div>
                        @endif

                        <form action="{{ route('games.guess-number.start') }}" method="POST" class="mb-3">
                            @csrf
                            <input type="hidden" name="difficulty" value="{{ $lastDifficulty }}">
                            <button type="submit" class="btn btn-outline-primary">
                                Play Again ({{ ucfirst($lastDifficulty) }})
                            </button>
                        </form>
                    @endif

                    @if($game)
                        <div class="mb-3">
                            <span class="badge bg-primary fs-6">Guesses made this round: {{ $game['attempts'] }}</span>
                        </div>

                        <div class="alert alert-info">
                            <strong>Current game:</strong>
                            {{ ucfirst($game['difficulty']) }} ({{ $game['min'] }} - {{ $game['max'] }})
                            <br>
                            <strong>Guesses used:</strong> {{ $game['attempts'] }}
                            @if($game['last_guess'] !== null)
                                <br>
                                <strong>Last guess:</strong> {{ $game['last_guess'] }}
                                <strong class="ms-3">Hint:</strong> {{ $game['hint'] }}
                            @endif
                            <br>
                            <strong>Current possible reward:</strong>
                            €{{ number_format(round($game['base_reward'] * pow(0.95, $game['attempts']), 2), 2) }}
                        </div>

                        <form action="{{ route('games.guess-number.guess') }}" method="POST" class="row g-2">
                            @csrf
                            <div class="col-md-8">
                                <label for="guess" class="form-label">Your guess</label>
                                <input
                                    type="number"
                                    name="guess"
                                    id="guess"
                                    class="form-control @error('guess') is-invalid @enderror"
                                    min="{{ $game['min'] }}"
                                    max="{{ $game['max'] }}"
                                    required
                                >
                                @error('guess')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-success w-100">Submit Guess</button>
                            </div>
                        </form>

                        <form action="{{ route('games.guess-number.reset') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary">Reset Game</button>
                        </form>
                    @endif

                    <hr class="my-4">

                    <h5>Top 10 Leaderboard</h5>
                    @if($leaderboard->isEmpty())
                        <p class="text-muted mb-0">No winners yet. Be the first!</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Player</th>
                                        <th>Difficulty</th>
                                        <th>Attempts</th>
                                        <th>Reward</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaderboard as $index => $score)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $score->user->name ?? 'Unknown' }}</td>
                                            <td class="text-capitalize">{{ $score->difficulty }}</td>
                                            <td>{{ $score->attempts }}</td>
                                            <td>€{{ number_format($score->reward, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
