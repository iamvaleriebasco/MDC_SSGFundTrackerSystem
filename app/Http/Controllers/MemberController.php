<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::withCount('transactions')->latest()->paginate(10);

        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string|max:20|unique:members,student_id',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:members,email',
            'course'     => 'required|string|max:100',
            'year_level' => 'required|string|max:20',
            'section'    => 'required|string|max:20',
            'position'   => 'nullable|string|max:100',
            'is_active'  => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Member::create($validated);

        return redirect()->route('members.index')
                         ->with('success', 'Member added successfully.');
    }

    public function show(Member $member)
    {
        $transactions = $member->transactions()
                               ->with(['fund', 'recorder'])
                               ->latest()
                               ->paginate(10);

        return view('members.show', compact('member', 'transactions'));
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'student_id' => 'required|string|max:20|unique:members,student_id,' . $member->id,
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:members,email,' . $member->id,
            'course'     => 'required|string|max:100',
            'year_level' => 'required|string|max:20',
            'section'    => 'required|string|max:20',
            'position'   => 'nullable|string|max:100',
            'is_active'  => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $member->update($validated);

        return redirect()->route('members.index')
                         ->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('members.index')
                         ->with('success', 'Member deleted successfully.');
    }
}
