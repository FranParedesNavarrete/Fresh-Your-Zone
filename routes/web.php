<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Favorite\FavoriteController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Information\InformationController;

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login/store', [AuthController::class, 'store'])->name('login.store');
Route::post('login', [AuthController::class, 'login'])->name('login.form');
Route::get('/register', [AuthController::class, 'registerIndex'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas de administración
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index')->middleware('auth');
Route::post('/admin/notification', [NotificationController::class, 'store'])->name('admin.notifications.send')->middleware('auth');
Route::post('/admin/delete-user', [UserController::class, 'destroy'])->name('admin.delete.user')->middleware('auth');
Route::get('/admin/products', [AdminController::class, 'productsIndex'])->name('admin.products.index')->middleware('auth');
Route::post('/admin/delete-product', [AdminController::class, 'destroyProduct'])->name('admin.delete.product')->middleware('auth');
Route::get('/admin/orders', [AdminController::class, 'ordersIndex'])->name('admin.orders.index')->middleware('auth');
Route::post('/admin/orders/update-status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.update.status')->middleware('auth');

// Rutas de productos
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/store', [ProductController::class, 'store'])-> name('products.store')->middleware('auth');
Route::post('/products/update/{product}', [ProductController::class, 'update'])->name('products.update')->middleware('auth');
Route::post('/products/delete/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('auth');
Route::post('/products/move-to-cart', [OrderController::class, 'moveToShoppingCart'])->name('products.move.to.cart')->middleware('auth');
Route::post('/products/remove-from-cart', [OrderController::class, 'removeFromShoppingCart'])->name('products.remove.from.cart')->middleware('auth');
Route::post('/products/prepare-to-buy',  [OrderController::class, 'prepareProducts'])->name('products.prepare.products')->middleware('auth');
Route::post('/products/review/{product}', [ProductController::class, 'review'])->name('products.review')->middleware('auth');

// Rutas de notificaciones
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index')->middleware('auth');

// Rutas de favoritos
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index')->middleware('auth');
Route::delete('/favorites/delete/{favorite}', [FavoriteController::class, 'destroy'])->name('favorites.destroy')->middleware('auth');
Route::post('/favorites/add/{favorite}', [FavoriteController::class, 'store'])->name('favorites.add')->middleware('auth');

// Rutas de perfil
Route::get('/profile', [UserController::class, 'index'])->name('profile.index')->middleware('auth');
Route::get('/profile/{user}', [UserController::class, 'show'])->name('profile.show')->middleware('auth');
Route::put('/profile/{user}/update', [UserController::class, 'update'])->name('profile.update')->middleware('auth');
Route::put('/profile/password', [UserController::class, 'changePassword'])->name('profile.change-password')->middleware('auth');
Route::post('/profile/{user}/change-avatar', [UserController::class, 'changeAvatar'])->name('profile.change-avatar')->middleware('auth');
Route::get('/profile/{user}/notifications', [UserController::class, 'notifications'])->name('profile.notifications')->middleware('auth');
Route::get('/profile/{user}/favorites', [UserController::class, 'favorites'])->name('profile.favorites')->middleware('auth');
Route::get('/profile/{user}/history', [UserController::class, 'history'])->name('profile.history')->middleware('auth');
Route::get('/profile/{user}/seller/manage-products', [UserController::class, 'manageProducts'])->name('profile.manage-products')->middleware('auth');
Route::get('/profile/{user}/seller/sales-history', [UserController::class, 'salesHistory'])-> name('profile.sales-history')->middleware('auth');
Route::post('/profile/save-phone', [UserController::class, 'savePhone'])->name('profile.save-phone')->middleware('auth');
Route::post('/profile/save-address', [UserController::class, 'saveAddress'])->name('profile.save-address')->middleware('auth');
Route::post('/profile/change-role', [UserController::class, 'changeRole'])->name('profile.change-role')->middleware('auth');

// Rutas de pedidos
Route::get('/orders/resume', [OrderController::class, 'index'])->name('orders.index')->middleware('auth');
Route::get('/orders/cart', [OrderController::class, 'shoppingCart'])->name('orders.cart')->middleware('auth');
Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/success', [OrderController::class, 'success'])->name('orders.success')->middleware('auth');

// Rutas de información
Route::get('about-us', [InformationController::class, 'aboutUs'])->name('about.us');
Route::get('information/terms-conditions', [InformationController::class, 'termsAndConditions'])->name('information.terms.conditions');
Route::get('information/privacy-policy', [InformationController::class, 'privacyPolicy'])->name('information.privacy-policy');
Route::get('information/cookies-policy', [InformationController::class, 'cookiesPolicy'])->name('information.cookies.policy');
Route::get('information/legal-policy', [InformationController::class, 'legalNotice'])->name('information.legal.policy');

// Ruta de inicio
Route::get('/', [ProductController::class, 'homeProducts'])->name('index');
