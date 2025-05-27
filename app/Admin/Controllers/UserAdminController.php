<?php
//
//namespace App\Admin\Controllers;
//
//use App\Models\User;
//use Encore\Admin\Controllers\AdminController;
//use Encore\Admin\Form;
//use Encore\Admin\Grid;
//use Encore\Admin\Show;
//use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\Storage;
//
//class UserAdminController extends AdminController
//{
//    protected $title = 'Управление пользователями';
//
//    protected function grid()
//    {
//        $grid = new Grid(new User());
//
//        $grid->column('id', __('ID'))->sortable();
//        $grid->column('role', __('Роль'))->sortable();
//        $grid->column('nickname', __('Ник'))->sortable();
//        $grid->column('email', __('Почта'))->sortable();
//        $grid->column('name', __('Имя'))->sortable();
//        $grid->column('last_name', __('Фамилия'))->sortable();
//        $grid->column('birth_date', __('Дата рождения'))->sortable()->display(function ($date) {
//            return date('d.m.Y', strtotime($date));
//        });
//        $grid->column('about', __('Обо мне'))->limit(50);
//        $grid->column('icon', __('Иконка'))->image('/storage/', 50, 50);
//        $grid->column('head_profile', __('Шапка профиля'))->image('/storage/', 100, 50);
//
//        return $grid;
//    }
//
//    protected function detail($id)
//    {
//        $show = new Show(User::findOrFail($id));
//
//        $show->field('id', __('ID'));
//        $show->field('role', __('Роль'));
//        $show->field('nickname', __('Ник'));
//        $show->field('email', __('Почта'));
//        $show->field('name', __('Имя'));
//        $show->field('last_name', __('Фамилия'));
//        $show->field('birth_date', __('Дата рождения'))->as(function ($date) {
//            return date('d.m.Y', strtotime($date));
//        });
//        $show->field('about', __('Обо мне'));
//        $show->field('icon', __('Иконка'))->image('/storage/');
//        $show->field('head_profile', __('Шапка профиля'))->image('/storage/');
//
//        return $show;
//    }
//
//    protected function form()
//    {
//        $form = new Form(new User());
//
//        $form->display('id', __('ID'));
//        $form->number('role', __('Роль'))->rules('required|integer');
//        $form->text('nickname', __('Ник'))->rules('required|max:255');
//        $form->email('email', __('Почта'))->rules('required|email|max:255');
//        $form->text('name', __('Имя'))->rules('required|max:255');
//        $form->text('last_name', __('Фамилия'))->rules('required|max:255');
//        $form->date('birth_date', __('Дата рождения'))->rules('required|date');
//        $form->password('password', __('Пароль'))->rules('nullable|min:6');
//        $form->textarea('about', __('Обо мне'))->rules('nullable|max:1000');
//
//        $form->image('icon', __('Иконка'))
//            ->uniqueName()
//            ->move('icon_user')
//            ->removable();
//
//        $form->image('head_profile', __('Шапка профиля'))
//            ->uniqueName()
//            ->move('head_profile')
//            ->removable();
//
//        $form->saving(function (Form $form) {
//            if ($form->password && $form->model()->password != $form->password) {
//                $form->password = Hash::make($form->password);
//            }
//            if ($form->isEditing() && $form->model()->icon && !$form->icon) {
//                Storage::delete('public/icon_user/' . $form->model()->icon);
//            }
//            if ($form->isEditing() && $form->model()->head_profile && !$form->head_profile) {
//                Storage::delete('public/head_profile/' . $form->model()->head_profile);
//            }
//        });
//
//        return $form;
//    }
//}