<?php

namespace App\Http\Controllers;

use App\Services\Calculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

/**
 * TEST MENU - Calculator form.
 * GET  /calc        shows the empty form
 * POST /calc        validates the input, calculates, shows the result + the form again
 * POST /calc/clear  clears the calculation history kept in the session
 */
class CalculatorController extends Controller
{
    private const HISTORY_KEY  = 'calc_history';
    private const HISTORY_SIZE = 5;

    // Dependency injection: Laravel creates the Calculator object for us
    public function __construct(private readonly Calculator $calculator)
    {
    }

    public function showForm(Request $request): View
    {
        return view('calc', [
            'a'          => old('a', ''),
            'b'          => old('b', ''),
            'op'         => old('op', 'add'),
            'symbol'     => null,
            'result'     => null,
            'error'      => null,
            'history'    => $request->session()->get(self::HISTORY_KEY, []),
            'operations' => Calculator::OPERATIONS,
        ]);
    }

    public function calculate(Request $request): View
    {
        // Server-side validation: on failure Laravel redirects back to /calc with the errors
        $validated = $request->validate([
            'a'  => 'required|numeric|between:-1000000,1000000',
            'b'  => 'required|numeric|between:-1000000,1000000',
            'op' => 'required|in:' . implode(',', array_keys(Calculator::OPERATIONS)),
        ]);

        $a  = (float) $validated['a'];
        $b  = (float) $validated['b'];
        $op = $validated['op'];

        $data = [
            'a'          => $this->calculator->format($a),
            'b'          => $this->calculator->format($b),
            'op'         => $op,
            'symbol'     => Calculator::OPERATIONS[$op],
            'result'     => null,
            'error'      => null,
            'operations' => Calculator::OPERATIONS,
        ];

        try {
            $data['result'] = $this->calculator->format($this->calculator->calculate($op, $a, $b));

            // Save to the history (newest first, at most 5 items)
            $line    = $data['a'] . ' ' . $data['symbol'] . ' ' . $data['b'] . ' = ' . $data['result'];
            $history = $request->session()->get(self::HISTORY_KEY, []);
            array_unshift($history, $line);
            $request->session()->put(self::HISTORY_KEY, array_slice($history, 0, self::HISTORY_SIZE));
        } catch (InvalidArgumentException $exception) {
            // e.g. division by zero: show the message instead of crashing
            $data['error'] = $exception->getMessage();
        }

        $data['history'] = $request->session()->get(self::HISTORY_KEY, []);

        return view('calc', $data);
    }

    public function clearHistory(Request $request): RedirectResponse
    {
        $request->session()->forget(self::HISTORY_KEY);

        return redirect()->route('calc.form')->with('success', 'Calculation history cleared.');
    }
}
