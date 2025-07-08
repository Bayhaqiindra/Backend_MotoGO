<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use Illuminate\Http\Request;
use Exception;

class ServiceController extends Controller
{
    /**
     * Menambahkan layanan baru
     * 
     * @param ServiceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function AddService(ServiceRequest $request)
    {
        try {
            // Menambahkan layanan baru
            $service = new Service();
            $service->service_name = $request->service_name;
            $service->service_cost = $request->service_cost;
            $service->save();

            return response()->json([
                'message' => 'Layanan berhasil ditambahkan',
                'status_code' => 201,
                'data' => $service
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan layanan: ' . $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ], 500);
        }
    }

    /**
     * Mengambil semua layanan
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllServices()
    {
        try {
            $services = Service::all();
            return response()->json([
                'message' => 'Data layanan berhasil ditemukan',
                'status_code' => 200,
                'data' => $services
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data layanan: ' . $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ], 500);
        }
    }

    /**
     * Mengambil layanan berdasarkan ID
     * 
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getServiceById($id)
    {
        try {
            $service = Service::find($id);

            if (!$service) {
                return response()->json([
                    'message' => 'Layanan tidak ditemukan',
                    'status_code' => 404,
                    'data' => null
                ], 404);
            }

            return response()->json([
                'message' => 'Layanan berhasil ditemukan',
                'status_code' => 200,
                'data' => $service
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data layanan: ' . $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ], 500);
        }
    }

    /**
     * Mengupdate layanan berdasarkan ID
     * 
     * @param ServiceRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateService(ServiceRequest $request, $id)
    {
        try {
            // Mencari layanan berdasarkan ID
            $service = Service::find($id);

            if (!$service) {
                return response()->json([
                    'message' => 'Layanan tidak ditemukan',
                    'status_code' => 404,
                    'data' => null
                ], 404);
            }

            // Mengupdate layanan dengan data baru
            $service->service_name = $request->service_name;
            $service->service_cost = $request->service_cost;
            $service->save();

            return response()->json([
                'message' => 'Layanan berhasil diperbarui',
                'status_code' => 200,
                'data' => $service
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui layanan: ' . $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ], 500);
        }
    }
    
    /**
     * Menghapus layanan berdasarkan ID
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteService($id)
    {
        try {
            // Mencari layanan berdasarkan ID
            $service = Service::find($id);

            if (!$service) {
                return response()->json([
                    'message' => 'Layanan tidak ditemukan',
                    'status_code' => 404,
                    'data' => null
                ], 404);
            }

            // Menghapus layanan
            $service->delete();

            return response()->json([
                'message' => 'Layanan berhasil dihapus',
                'status_code' => 200,
                'data' => null
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus layanan: ' . $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ], 500);
        }
    }
}
