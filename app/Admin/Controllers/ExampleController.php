<?php
//
//namespace App\Admin\Controllers;
//
//use Encore\Admin\Controllers\AdminController;
//use Encore\Admin\Form;
//use Encore\Admin\Grid;
//use Encore\Admin\Show;
//
//class ExampleController extends AdminController
//{
//    /**
//     * Title for current resource.
//     *
//     * @var string
//     */
//    protected $title = 'Example controller';
//
//    /**
//     * Make a grid builder.
//     *
//     * @return Grid
//     */
//    protected function grid()
//    {
//        $grid = new Grid(new ExampleModel);
//
//        $grid->column('id', __('ID'))->sortable();
//        $grid->column('created_at', __('Created at'));
//        $grid->column('updated_at', __('Updated at'));
//
//        return $grid;
//    }
//
//    /**
//     * Make a show builder.
//     *
//     * @param mixed   $id
//     * @return Show
//     */
//    protected function detail($id)
//    {
//        $show = new Show(ExampleModel::findOrFail($id));
//
//        $show->field('id', __('ID'));
//        $show->field('created_at', __('Created at'));
//        $show->field('updated_at', __('Updated at'));
//
//        return $show;
//    }
//
//    /**
//     * Make a form builder.
//     *
//     * @return Form
//     */
//    protected function form()
//    {
//        $form = new Form(new ExampleModel);
//
//        $form->display('id', __('ID'));
//        $form->display('created_at', __('Created At'));
//        $form->display('updated_at', __('Updated At'));
//
//        return $form;
//    }
//}
