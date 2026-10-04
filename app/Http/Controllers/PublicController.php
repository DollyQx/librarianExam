<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\StudyMaterial;
use App\Models\Subject;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $subjects = Subject::where('is_active', true)->withCount('topics')->take(6)->get();
        $popularTests = Quiz::where('is_active', true)->with('subject')->latest()->take(6)->get();
        $recentMaterials = StudyMaterial::where('is_active', true)->with('subject')->latest()->take(4)->get();
        $recentVideos = Video::where('is_active', true)->with('subject')->latest()->take(3)->get();

        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'total_subjects' => Subject::where('is_active', true)->count(),
            'total_materials' => StudyMaterial::where('is_active', true)->count(),
            'total_tests' => Quiz::where('is_active', true)->count(),
        ];

        return view('pages.home', compact('subjects', 'popularTests', 'recentMaterials', 'recentVideos', 'stats'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function handleContactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        return back()->with('success', 'Thank you for reaching out! We have received your message.');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function subjects()
    {
        $subjects = Subject::where('is_active', true)
            ->with(['topics' => function ($q) {
                $q->where('is_active', true);
            }])
            ->get();

        return view('pages.subjects', compact('subjects'));
    }

    public function tests()
    {
        $quizzes = Quiz::where('is_active', true)
            ->with(['subject', 'topic'])
            ->withCount('questions')
            ->latest()
            ->paginate(12);

        return view('pages.tests', compact('quizzes'));
    }
}
