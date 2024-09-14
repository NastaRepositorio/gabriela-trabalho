<?php

use Livewire\Volt\Volt;

Volt::route('/', 'home')->name('home');
Volt::route('/login', 'auth.login')->name('login')->middleware('guest');
Volt::route('/register', 'auth.register')->name('register')->middleware('guest');
Volt::route('/profile', 'profile.index')->name('profile')->middleware('auth');
Volt::route('/my-posts', 'profile.posts')->name('my-posts')->middleware('auth');
Volt::route('/posts', 'posts.index')->name('posts')->middleware('auth');
Volt::route('/post/{post}', 'posts.show')->name('post.show')->middleware('auth');