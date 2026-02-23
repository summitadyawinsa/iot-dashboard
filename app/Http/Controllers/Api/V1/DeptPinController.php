<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DeptPinController extends Controller
{

  public function check_pin_delivery()
  {
    $department = request()->input('Department');
    $pin = request()->input('Pin');

    if (empty($department) || empty($pin)) {
      return response()->json([
        'success' => false,
        'message' => 'Department dan PIN harus diisi'
      ]);
    }

    if (strtolower($department) !== 'delivery') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak. Hanya department Delivery yang diperbolehkan.'
      ]);
    }

    $valid = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->where('Pin', $pin)
      ->exists();

    if ($valid) {
      session(['department' => $department]);
      return response()->json(['success' => true]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'PIN salah'
      ]);
    }
  }

  public function check_pin_delivery_job()
  {
    $department = request()->input('Department');
    $pin = request()->input('Pin');

    if (empty($department) || empty($pin)) {
      return response()->json([
        'success' => false,
        'message' => 'Department dan PIN harus diisi'
      ]);
    }

    if (strtolower($department) !== 'delivery_job') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak. Hanya department Delivery yang diperbolehkan.'
      ]);
    }

    $valid = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->where('Pin', $pin)
      ->exists();

    if ($valid) {
      session(['department' => $department]);
      return response()->json(['success' => true]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'PIN salah'
      ]);
    }
  }

  public function check_pin_delivery_monitoring()
  {
    $department = request()->input('Department');
    $pin = request()->input('Pin');

    if (empty($department) || empty($pin)) {
      return response()->json([
        'success' => false,
        'message' => 'Department dan PIN harus diisi'
      ]);
    }

    if (strtolower($department) !== 'delivery_monitoring') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak. Hanya department Delivery yang diperbolehkan.'
      ]);
    }

    $valid = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->where('Pin', $pin)
      ->exists();

    if ($valid) {
      session(['department' => $department]);
      return response()->json(['success' => true]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'PIN salah'
      ]);
    }
  }
  public function check_pin_delivery_finish_good()
  {
    $department = request()->input('Department');
    $pin = request()->input('Pin');

    if (empty($department) || empty($pin)) {
      return response()->json([
        'success' => false,
        'message' => 'Department dan PIN harus diisi'
      ]);
    }

    if (strtolower($department) !== 'delivery_finish_good') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak. Hanya department Delivery yang diperbolehkan.'
      ]);
    }

    $valid = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->where('Pin', $pin)
      ->exists();

    if ($valid) {
      session(['department' => $department]);
      return response()->json(['success' => true]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'PIN salah'
      ]);
    }
  }

  public function check_pin_delivery_mit()
  {
    $department = request()->input('Department');
    $pin = request()->input('Pin');

    if (empty($department) || empty($pin)) {
      return response()->json([
        'success' => false,
        'message' => 'Department dan PIN harus diisi'
      ]);
    }

    if (strtolower($department) !== 'delivery_mit') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak. Hanya department Delivery yang diperbolehkan.'
      ]);
    }

    $valid = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->where('Pin', $pin)
      ->exists();

    if ($valid) {
      session(['department' => $department]);
      return response()->json(['success' => true]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'PIN salah'
      ]);
    }
  }

  public function check_pin_delivery_cgr()
  {
    $department = request()->input('Department');
    $pin = request()->input('Pin');

    if (empty($department) || empty($pin)) {
      return response()->json([
        'success' => false,
        'message' => 'Department dan PIN harus diisi'
      ]);
    }

    if (strtolower($department) !== 'delivery_cgr') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak. Hanya department Delivery yang diperbolehkan.'
      ]);
    }

    $valid = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->where('Pin', $pin)
      ->exists();

    if ($valid) {
      session(['department' => $department]);
      return response()->json(['success' => true]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'PIN salah'
      ]);
    }
  }

  public function update_pin_delivery()
  {
    $department = request()->input('Department');
    $oldPin = request()->input('OldPin');
    $newPin = request()->input('NewPin');

    if (empty($department) || empty($oldPin) || empty($newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'Semua field harus diisi'
      ]);
    }

    if (strtolower($department) !== 'delivery') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak untuk department ini'
      ]);
    }

    if (!preg_match('/^\d{4}$/', $oldPin) || !preg_match('/^\d{4}$/', $newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'PIN harus 4 digit angka'
      ]);
    }

    $currentPin = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->value('Pin');

    if ($currentPin !== $oldPin) {
      return response()->json([
        'success' => false,
        'message' => 'PIN lama tidak sesuai'
      ]);
    }

    try {
      $updated = DB::connection('sqlsrv5')
        ->table('DepartmentPin')
        ->where('Department', $department)
        ->update(['Pin' => $newPin]);

      return response()->json([
        'success' => (bool) $updated,
        'message' => $updated ? 'PIN berhasil diperbarui' : 'Gagal memperbarui PIN'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem'
      ]);
    }
  }

  public function update_pin_delivery_job()
  {
    $department = request()->input('Department');
    $oldPin = request()->input('OldPin');
    $newPin = request()->input('NewPin');

    if (empty($department) || empty($oldPin) || empty($newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'Semua field harus diisi'
      ]);
    }

    if (strtolower($department) !== 'delivery_job') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak untuk department ini'
      ]);
    }

    if (!preg_match('/^\d{4}$/', $oldPin) || !preg_match('/^\d{4}$/', $newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'PIN harus 4 digit angka'
      ]);
    }

    $currentPin = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->value('Pin');

    if ($currentPin !== $oldPin) {
      return response()->json([
        'success' => false,
        'message' => 'PIN lama tidak sesuai'
      ]);
    }

    try {
      $updated = DB::connection('sqlsrv5')
        ->table('DepartmentPin')
        ->where('Department', $department)
        ->update(['Pin' => $newPin]);

      return response()->json([
        'success' => (bool) $updated,
        'message' => $updated ? 'PIN berhasil diperbarui' : 'Gagal memperbarui PIN'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem'
      ]);
    }
  }

  public function update_pin_delivery_finish_good()
  {
    $department = request()->input('Department');
    $oldPin = request()->input('OldPin');
    $newPin = request()->input('NewPin');

    if (empty($department) || empty($oldPin) || empty($newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'Semua field harus diisi'
      ]);
    }

    if (strtolower($department) !== 'delivery_finish_good') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak untuk department ini'
      ]);
    }

    if (!preg_match('/^\d{4}$/', $oldPin) || !preg_match('/^\d{4}$/', $newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'PIN harus 4 digit angka'
      ]);
    }

    $currentPin = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->value('Pin');

    if ($currentPin !== $oldPin) {
      return response()->json([
        'success' => false,
        'message' => 'PIN lama tidak sesuai'
      ]);
    }

    try {
      $updated = DB::connection('sqlsrv5')
        ->table('DepartmentPin')
        ->where('Department', $department)
        ->update(['Pin' => $newPin]);

      return response()->json([
        'success' => (bool) $updated,
        'message' => $updated ? 'PIN berhasil diperbarui' : 'Gagal memperbarui PIN'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem'
      ]);
    }
  }


  public function update_pin_delivery_mit()
  {
    $department = request()->input('Department');
    $oldPin = request()->input('OldPin');
    $newPin = request()->input('NewPin');

    if (empty($department) || empty($oldPin) || empty($newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'Semua field harus diisi'
      ]);
    }

    if (strtolower($department) !== 'delivery_mit') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak untuk department ini'
      ]);
    }

    if (!preg_match('/^\d{4}$/', $oldPin) || !preg_match('/^\d{4}$/', $newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'PIN harus 4 digit angka'
      ]);
    }

    $currentPin = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->value('Pin');

    if ($currentPin !== $oldPin) {
      return response()->json([
        'success' => false,
        'message' => 'PIN lama tidak sesuai'
      ]);
    }

    try {
      $updated = DB::connection('sqlsrv5')
        ->table('DepartmentPin')
        ->where('Department', $department)
        ->update(['Pin' => $newPin]);

      return response()->json([
        'success' => (bool) $updated,
        'message' => $updated ? 'PIN berhasil diperbarui' : 'Gagal memperbarui PIN'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem'
      ]);
    }
  }

  public function update_pin_delivery_cgr()
  {
    $department = request()->input('Department');
    $oldPin = request()->input('OldPin');
    $newPin = request()->input('NewPin');

    if (empty($department) || empty($oldPin) || empty($newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'Semua field harus diisi'
      ]);
    }

    if (strtolower($department) !== 'delivery_cgr') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak untuk department ini'
      ]);
    }

    if (!preg_match('/^\d{4}$/', $oldPin) || !preg_match('/^\d{4}$/', $newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'PIN harus 4 digit angka'
      ]);
    }

    $currentPin = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->value('Pin');

    if ($currentPin !== $oldPin) {
      return response()->json([
        'success' => false,
        'message' => 'PIN lama tidak sesuai'
      ]);
    }

    try {
      $updated = DB::connection('sqlsrv5')
        ->table('DepartmentPin')
        ->where('Department', $department)
        ->update(['Pin' => $newPin]);

      return response()->json([
        'success' => (bool) $updated,
        'message' => $updated ? 'PIN berhasil diperbarui' : 'Gagal memperbarui PIN'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem'
      ]);
    }
  }

  public function check_pin_finance_profit()
  {
    $department = request()->input('Department');
    $pin = request()->input('Pin');

    if (empty($department) || empty($pin)) {
      return response()->json([
        'success' => false,
        'message' => 'Department dan PIN harus diisi'
      ]);
    }

    if (strtolower($department) !== 'finance_profit') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak. Hanya department Finance yang diperbolehkan.'
      ]);
    }

    $valid = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->where('Pin', $pin)
      ->exists();

    if ($valid) {
      session(['department' => $department]);
      return response()->json(['success' => true]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'PIN salah'
      ]);
    }
  }

  public function check_pin_finance_model()
  {
    $department = request()->input('Department');
    $pin = request()->input('Pin');

    if (empty($department) || empty($pin)) {
      return response()->json([
        'success' => false,
        'message' => 'Department dan PIN harus diisi'
      ]);
    }

    if (strtolower($department) !== 'finance_model') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak. Hanya department Finance yang diperbolehkan.'
      ]);
    }

    $valid = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->where('Pin', $pin)
      ->exists();

    if ($valid) {
      session(['department' => $department]);
      return response()->json(['success' => true]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'PIN salah'
      ]);
    }
  }

  public function check_pin_finance_invoice()
  {
    $department = request()->input('Department');
    $pin = request()->input('Pin');

    if (empty($department) || empty($pin)) {
      return response()->json([
        'success' => false,
        'message' => 'Department dan PIN harus diisi'
      ]);
    }

    if (strtolower($department) !== 'finance_invoice') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak. Hanya department Finance yang diperbolehkan.'
      ]);
    }

    $valid = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->where('Pin', $pin)
      ->exists();

    if ($valid) {
      session(['department' => $department]);
      return response()->json(['success' => true]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'PIN salah'
      ]);
    }
  }
  public function check_pin_finance_rcd()
  {
    $department = request()->input('Department');
    $pin = request()->input('Pin');

    if (empty($department) || empty($pin)) {
      return response()->json([
        'success' => false,
        'message' => 'Department dan PIN harus diisi'
      ]);
    }

    if (strtolower($department) !== 'finance_rcd') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak. Hanya department Finance yang diperbolehkan.'
      ]);
    }

    $valid = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->where('Pin', $pin)
      ->exists();

    if ($valid) {
      session(['department' => $department]);
      return response()->json(['success' => true]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'PIN salah'
      ]);
    }
  }

  public function update_pin_finance_profit()
  {
    $department = request()->input('Department');
    $oldPin = request()->input('OldPin');
    $newPin = request()->input('NewPin');

    if (empty($department) || empty($oldPin) || empty($newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'Semua field harus diisi'
      ]);
    }

    if (strtolower($department) !== 'finance_profit') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak untuk department ini'
      ]);
    }

    if (!preg_match('/^\d{4}$/', $oldPin) || !preg_match('/^\d{4}$/', $newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'PIN harus 4 digit angka'
      ]);
    }

    $currentPin = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->value('Pin');

    if ($currentPin !== $oldPin) {
      return response()->json([
        'success' => false,
        'message' => 'PIN lama tidak sesuai'
      ]);
    }

    try {
      $updated = DB::connection('sqlsrv5')
        ->table('DepartmentPin')
        ->where('Department', $department)
        ->update(['Pin' => $newPin]);

      return response()->json([
        'success' => (bool) $updated,
        'message' => $updated ? 'PIN berhasil diperbarui' : 'Gagal memperbarui PIN'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem'
      ]);
    }
  }
  public function update_pin_finance_model()
  {
    $department = request()->input('Department');
    $oldPin = request()->input('OldPin');
    $newPin = request()->input('NewPin');

    if (empty($department) || empty($oldPin) || empty($newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'Semua field harus diisi'
      ]);
    }

    if (strtolower($department) !== 'finance_model') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak untuk department ini'
      ]);
    }

    if (!preg_match('/^\d{4}$/', $oldPin) || !preg_match('/^\d{4}$/', $newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'PIN harus 4 digit angka'
      ]);
    }

    $currentPin = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->value('Pin');

    if ($currentPin !== $oldPin) {
      return response()->json([
        'success' => false,
        'message' => 'PIN lama tidak sesuai'
      ]);
    }

    try {
      $updated = DB::connection('sqlsrv5')
        ->table('DepartmentPin')
        ->where('Department', $department)
        ->update(['Pin' => $newPin]);

      return response()->json([
        'success' => (bool) $updated,
        'message' => $updated ? 'PIN berhasil diperbarui' : 'Gagal memperbarui PIN'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem'
      ]);
    }
  }
  public function update_pin_finance_invoice()
  {
    $department = request()->input('Department');
    $oldPin = request()->input('OldPin');
    $newPin = request()->input('NewPin');

    if (empty($department) || empty($oldPin) || empty($newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'Semua field harus diisi'
      ]);
    }

    if (strtolower($department) !== 'finance_invoice') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak untuk department ini'
      ]);
    }

    if (!preg_match('/^\d{4}$/', $oldPin) || !preg_match('/^\d{4}$/', $newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'PIN harus 4 digit angka'
      ]);
    }

    $currentPin = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->value('Pin');

    if ($currentPin !== $oldPin) {
      return response()->json([
        'success' => false,
        'message' => 'PIN lama tidak sesuai'
      ]);
    }

    try {
      $updated = DB::connection('sqlsrv5')
        ->table('DepartmentPin')
        ->where('Department', $department)
        ->update(['Pin' => $newPin]);

      return response()->json([
        'success' => (bool) $updated,
        'message' => $updated ? 'PIN berhasil diperbarui' : 'Gagal memperbarui PIN'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem'
      ]);
    }
  }
  public function update_pin_finance_rcd()
  {
    $department = request()->input('Department');
    $oldPin = request()->input('OldPin');
    $newPin = request()->input('NewPin');

    if (empty($department) || empty($oldPin) || empty($newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'Semua field harus diisi'
      ]);
    }

    if (strtolower($department) !== 'finance_rcd') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak untuk department ini'
      ]);
    }

    if (!preg_match('/^\d{4}$/', $oldPin) || !preg_match('/^\d{4}$/', $newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'PIN harus 4 digit angka'
      ]);
    }

    $currentPin = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->value('Pin');

    if ($currentPin !== $oldPin) {
      return response()->json([
        'success' => false,
        'message' => 'PIN lama tidak sesuai'
      ]);
    }

    try {
      $updated = DB::connection('sqlsrv5')
        ->table('DepartmentPin')
        ->where('Department', $department)
        ->update(['Pin' => $newPin]);

      return response()->json([
        'success' => (bool) $updated,
        'message' => $updated ? 'PIN berhasil diperbarui' : 'Gagal memperbarui PIN'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem'
      ]);
    }
  }
  public function check_pin_ppic_job()
  {
    $department = request()->input('Department');
    $pin = request()->input('Pin');

    if (empty($department) || empty($pin)) {
      return response()->json([
        'success' => false,
        'message' => 'Department dan PIN harus diisi'
      ]);
    }

    if (strtolower($department) !== 'ppic_job') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak'
      ]);
    }

    $valid = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->where('Pin', $pin)
      ->exists();

    if ($valid) {
      session(['department' => $department]);
      return response()->json(['success' => true]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'PIN salah'
      ]);
    }
  }

  public function update_pin_ppic_job()
  {
    $department = request()->input('Department');
    $oldPin = request()->input('OldPin');
    $newPin = request()->input('NewPin');

    if (empty($department) || empty($oldPin) || empty($newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'Semua field harus diisi'
      ]);
    }

    if (strtolower($department) !== 'ppic_job') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak untuk department ini'
      ]);
    }

    if (!preg_match('/^\d{4}$/', $oldPin) || !preg_match('/^\d{4}$/', $newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'PIN harus 4 digit angka'
      ]);
    }

    $currentPin = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->value('Pin');

    if ($currentPin !== $oldPin) {
      return response()->json([
        'success' => false,
        'message' => 'PIN lama tidak sesuai'
      ]);
    }

    try {
      $updated = DB::connection('sqlsrv5')
        ->table('DepartmentPin')
        ->where('Department', $department)
        ->update(['Pin' => $newPin]);

      return response()->json([
        'success' => (bool) $updated,
        'message' => $updated ? 'PIN berhasil diperbarui' : 'Gagal memperbarui PIN'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem'
      ]);
    }
  }

  public function check_pin_ppic_stock()
  {
    $department = request()->input('Department');
    $pin = request()->input('Pin');

    if (empty($department) || empty($pin)) {
      return response()->json([
        'success' => false,
        'message' => 'Department dan PIN harus diisi'
      ]);
    }

    if (strtolower($department) !== 'ppic_stock') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak'
      ]);
    }

    $valid = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->where('Pin', $pin)
      ->exists();

    if ($valid) {
      session(['department' => $department]);
      return response()->json(['success' => true]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'PIN salah'
      ]);
    }
  }

  public function update_pin_ppic_stock()
  {
    $department = request()->input('Department');
    $oldPin = request()->input('OldPin');
    $newPin = request()->input('NewPin');

    if (empty($department) || empty($oldPin) || empty($newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'Semua field harus diisi'
      ]);
    }

    if (strtolower($department) !== 'ppic_stock') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak untuk department ini'
      ]);
    }

    if (!preg_match('/^\d{4}$/', $oldPin) || !preg_match('/^\d{4}$/', $newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'PIN harus 4 digit angka'
      ]);
    }

    $currentPin = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->value('Pin');

    if ($currentPin !== $oldPin) {
      return response()->json([
        'success' => false,
        'message' => 'PIN lama tidak sesuai'
      ]);
    }

    try {
      $updated = DB::connection('sqlsrv5')
        ->table('DepartmentPin')
        ->where('Department', $department)
        ->update(['Pin' => $newPin]);

      return response()->json([
        'success' => (bool) $updated,
        'message' => $updated ? 'PIN berhasil diperbarui' : 'Gagal memperbarui PIN'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem'
      ]);
    }
  }
  public function check_pin_production()
  {
    $department = request()->input('Department');
    $pin = request()->input('Pin');

    if (empty($department) || empty($pin)) {
      return response()->json([
        'success' => false,
        'message' => 'Department dan PIN harus diisi'
      ]);
    }

    if (strtolower($department) !== 'production') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak.'
      ]);
    }

    $valid = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->where('Pin', $pin)
      ->exists();

    if ($valid) {
      session(['department' => $department]);
      return response()->json(['success' => true]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'PIN salah'
      ]);
    }
  }

  public function update_pin_production()
  {
    $department = request()->input('Department');
    $oldPin = request()->input('OldPin');
    $newPin = request()->input('NewPin');

    if (empty($department) || empty($oldPin) || empty($newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'Semua field harus diisi'
      ]);
    }

    if (strtolower($department) !== 'production') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak untuk department ini'
      ]);
    }

    if (!preg_match('/^\d{4}$/', $oldPin) || !preg_match('/^\d{4}$/', $newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'PIN harus 4 digit angka'
      ]);
    }

    $currentPin = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->value('Pin');

    if ($currentPin !== $oldPin) {
      return response()->json([
        'success' => false,
        'message' => 'PIN lama tidak sesuai'
      ]);
    }

    try {
      $updated = DB::connection('sqlsrv5')
        ->table('DepartmentPin')
        ->where('Department', $department)
        ->update(['Pin' => $newPin]);

      return response()->json([
        'success' => (bool) $updated,
        'message' => $updated ? 'PIN berhasil diperbarui' : 'Gagal memperbarui PIN'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem'
      ]);
    }
  }

  public function check_pin_purchasing()
  {
    $department = request()->input('Department');
    $pin = request()->input('Pin');

    if (empty($department) || empty($pin)) {
      return response()->json([
        'success' => false,
        'message' => 'Department dan PIN harus diisi'
      ]);
    }

    if (strtolower($department) !== 'purchasing') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak.'
      ]);
    }

    $valid = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->where('Pin', $pin)
      ->exists();

    if ($valid) {
      session(['department' => $department]);
      return response()->json(['success' => true]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'PIN salah'
      ]);
    }
  }

  public function check_pin_purchasing_pr()
  {
    $department = request()->input('Department');
    $pin = request()->input('Pin');

    if (empty($department) || empty($pin)) {
      return response()->json([
        'success' => false,
        'message' => 'Department dan PIN harus diisi'
      ]);
    }

    if (strtolower($department) !== 'purchasing_pr') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak.'
      ]);
    }

    $valid = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->where('Pin', $pin)
      ->exists();

    if ($valid) {
      session(['department' => $department]);
      return response()->json(['success' => true]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'PIN salah'
      ]);
    }
  }

  public function check_pin_purchasing_project()
  {
    $department = request()->input('Department');
    $pin = request()->input('Pin');

    if (empty($department) || empty($pin)) {
      return response()->json([
        'success' => false,
        'message' => 'Department dan PIN harus diisi'
      ]);
    }

    if (strtolower($department) !== 'purchasing_project') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak.'
      ]);
    }

    $valid = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->where('Pin', $pin)
      ->exists();

    if ($valid) {
      session(['department' => $department]);
      return response()->json(['success' => true]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'PIN salah'
      ]);
    }
  }

  public function check_pin_purchasing_ppic()
  {
    $department = request()->input('Department');
    $pin = request()->input('Pin');

    if (empty($department) || empty($pin)) {
      return response()->json([
        'success' => false,
        'message' => 'Department dan PIN harus diisi'
      ]);
    }

    if (strtolower($department) !== 'purchasing_ppic') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak.'
      ]);
    }

    $valid = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->where('Pin', $pin)
      ->exists();

    if ($valid) {
      session(['department' => $department]);
      return response()->json(['success' => true]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'PIN salah'
      ]);
    }
  }

  public function check_pin_purchasing_reguler()
  {
    $department = request()->input('Department');
    $pin = request()->input('Pin');

    if (empty($department) || empty($pin)) {
      return response()->json([
        'success' => false,
        'message' => 'Department dan PIN harus diisi'
      ]);
    }

    if (strtolower($department) !== 'purchasing_reguler') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak.'
      ]);
    }

    $valid = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->where('Pin', $pin)
      ->exists();

    if ($valid) {
      session(['department' => $department]);
      return response()->json(['success' => true]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'PIN salah'
      ]);
    }
  }

  public function update_pin_purchasing()
  {
    $department = request()->input('Department');
    $oldPin = request()->input('OldPin');
    $newPin = request()->input('NewPin');

    if (empty($department) || empty($oldPin) || empty($newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'Semua field harus diisi'
      ]);
    }

    if (strtolower($department) !== 'purchasing') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak untuk department ini'
      ]);
    }

    if (!preg_match('/^\d{4}$/', $oldPin) || !preg_match('/^\d{4}$/', $newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'PIN harus 4 digit angka'
      ]);
    }

    $currentPin = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->value('Pin');

    if ($currentPin !== $oldPin) {
      return response()->json([
        'success' => false,
        'message' => 'PIN lama tidak sesuai'
      ]);
    }

    try {
      $updated = DB::connection('sqlsrv5')
        ->table('DepartmentPin')
        ->where('Department', $department)
        ->update(['Pin' => $newPin]);

      return response()->json([
        'success' => (bool) $updated,
        'message' => $updated ? 'PIN berhasil diperbarui' : 'Gagal memperbarui PIN'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem'
      ]);
    }
  }

  public function update_pin_purchasing_pr()
  {
    $department = request()->input('Department');
    $oldPin = request()->input('OldPin');
    $newPin = request()->input('NewPin');

    if (empty($department) || empty($oldPin) || empty($newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'Semua field harus diisi'
      ]);
    }

    if (strtolower($department) !== 'purchasing_pr') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak untuk department ini'
      ]);
    }

    if (!preg_match('/^\d{4}$/', $oldPin) || !preg_match('/^\d{4}$/', $newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'PIN harus 4 digit angka'
      ]);
    }

    $currentPin = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->value('Pin');

    if ($currentPin !== $oldPin) {
      return response()->json([
        'success' => false,
        'message' => 'PIN lama tidak sesuai'
      ]);
    }

    try {
      $updated = DB::connection('sqlsrv5')
        ->table('DepartmentPin')
        ->where('Department', $department)
        ->update(['Pin' => $newPin]);

      return response()->json([
        'success' => (bool) $updated,
        'message' => $updated ? 'PIN berhasil diperbarui' : 'Gagal memperbarui PIN'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem'
      ]);
    }
  }

  public function update_pin_purchasing_project()
  {
    $department = request()->input('Department');
    $oldPin = request()->input('OldPin');
    $newPin = request()->input('NewPin');

    if (empty($department) || empty($oldPin) || empty($newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'Semua field harus diisi'
      ]);
    }

    if (strtolower($department) !== 'purchasing_project') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak untuk department ini'
      ]);
    }

    if (!preg_match('/^\d{4}$/', $oldPin) || !preg_match('/^\d{4}$/', $newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'PIN harus 4 digit angka'
      ]);
    }

    $currentPin = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->value('Pin');

    if ($currentPin !== $oldPin) {
      return response()->json([
        'success' => false,
        'message' => 'PIN lama tidak sesuai'
      ]);
    }

    try {
      $updated = DB::connection('sqlsrv5')
        ->table('DepartmentPin')
        ->where('Department', $department)
        ->update(['Pin' => $newPin]);

      return response()->json([
        'success' => (bool) $updated,
        'message' => $updated ? 'PIN berhasil diperbarui' : 'Gagal memperbarui PIN'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem'
      ]);
    }
  }

  public function update_pin_purchasing_ppic()
  {
    $department = request()->input('Department');
    $oldPin = request()->input('OldPin');
    $newPin = request()->input('NewPin');

    if (empty($department) || empty($oldPin) || empty($newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'Semua field harus diisi'
      ]);
    }

    if (strtolower($department) !== 'purchasing_ppic') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak untuk department ini'
      ]);
    }

    if (!preg_match('/^\d{4}$/', $oldPin) || !preg_match('/^\d{4}$/', $newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'PIN harus 4 digit angka'
      ]);
    }

    $currentPin = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->value('Pin');

    if ($currentPin !== $oldPin) {
      return response()->json([
        'success' => false,
        'message' => 'PIN lama tidak sesuai'
      ]);
    }

    try {
      $updated = DB::connection('sqlsrv5')
        ->table('DepartmentPin')
        ->where('Department', $department)
        ->update(['Pin' => $newPin]);

      return response()->json([
        'success' => (bool) $updated,
        'message' => $updated ? 'PIN berhasil diperbarui' : 'Gagal memperbarui PIN'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem'
      ]);
    }
  }

  public function update_pin_purchasing_reguler()
  {
    $department = request()->input('Department');
    $oldPin = request()->input('OldPin');
    $newPin = request()->input('NewPin');

    if (empty($department) || empty($oldPin) || empty($newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'Semua field harus diisi'
      ]);
    }

    if (strtolower($department) !== 'purchasing_reguler') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak untuk department ini'
      ]);
    }

    if (!preg_match('/^\d{4}$/', $oldPin) || !preg_match('/^\d{4}$/', $newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'PIN harus 4 digit angka'
      ]);
    }

    $currentPin = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->value('Pin');

    if ($currentPin !== $oldPin) {
      return response()->json([
        'success' => false,
        'message' => 'PIN lama tidak sesuai'
      ]);
    }

    try {
      $updated = DB::connection('sqlsrv5')
        ->table('DepartmentPin')
        ->where('Department', $department)
        ->update(['Pin' => $newPin]);

      return response()->json([
        'success' => (bool) $updated,
        'message' => $updated ? 'PIN berhasil diperbarui' : 'Gagal memperbarui PIN'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem'
      ]);
    }
  }

  public function check_pin_sales()
  {
    $department = request()->input('Department');
    $pin = request()->input('Pin');

    if (empty($department) || empty($pin)) {
      return response()->json([
        'success' => false,
        'message' => 'Department dan PIN harus diisi'
      ]);
    }

    if (strtolower($department) !== 'sales') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak.'
      ]);
    }

    $valid = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->where('Pin', $pin)
      ->exists();

    if ($valid) {
      session(['department' => $department]);
      return response()->json(['success' => true]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'PIN salah'
      ]);
    }
  }

  public function update_pin_sales()
  {
    $department = request()->input('Department');
    $oldPin = request()->input('OldPin');
    $newPin = request()->input('NewPin');

    if (empty($department) || empty($oldPin) || empty($newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'Semua field harus diisi'
      ]);
    }

    if (strtolower($department) !== 'sales') {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak untuk department ini'
      ]);
    }

    if (!preg_match('/^\d{4}$/', $oldPin) || !preg_match('/^\d{4}$/', $newPin)) {
      return response()->json([
        'success' => false,
        'message' => 'PIN harus 4 digit angka'
      ]);
    }

    $currentPin = DB::connection('sqlsrv5')
      ->table('DepartmentPin')
      ->where('Department', $department)
      ->value('Pin');

    if ($currentPin !== $oldPin) {
      return response()->json([
        'success' => false,
        'message' => 'PIN lama tidak sesuai'
      ]);
    }

    try {
      $updated = DB::connection('sqlsrv5')
        ->table('DepartmentPin')
        ->where('Department', $department)
        ->update(['Pin' => $newPin]);

      return response()->json([
        'success' => (bool) $updated,
        'message' => $updated ? 'PIN berhasil diperbarui' : 'Gagal memperbarui PIN'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem'
      ]);
    }
  }
}
