<?php
namespace Zusamarehan\Tourify\Http\Controllers;


use App\Http\Controllers\Controller;

use Zusamarehan\Tourify\Model\Tourifies;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class TourifyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $allTours = Tourifies::selectRaw('route as `route`,file_name as `fileName`,id as `id`,created_at as `created_at`,updated_at as `updated_at`')->get();
        return view('Tourify::list', compact('allTours'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        $allGetRoutes = [];

        foreach (Route::getRoutes()->getRoutes() as $route) {
            $action = $route->getAction();
            if (array_key_exists('as', $action) && $route->methods[0] === 'GET') {
                $allGetRoutes[] = $action['as'];
            }
        }
        $allExistingTours = Tourifies::selectRaw('route')->get()->toArray();
        $existingTour = [];
        foreach ($allExistingTours as $tour){
            array_push($existingTour, $tour['route']);
        }

        return view('Tourify::create', compact('allGetRoutes', 'existingTour'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $parsedTour = json_decode($request->input('tour'), true);
        $newFile = Storage::put('public/tourify/'.$parsedTour['id'].'.js', 'let tour = '.$request->input('tour').';hopscotch.startTour(tour);');
        if($newFile){

            self::createNewTour($request->input('route'), $parsedTour['id']);
            Artisan::call('view:clear');

            $allTours = Tourifies::selectRaw('route as `route`,file_name as `fileName`,id as `id`,created_at as `created_at`,updated_at as `updated_at`')->get();
            Session::flash('msg', 'Tour Created Successfully!');
            Session::flash('status', 'success');
            return response(['status' => true, 'msg' => 'Tour Created Successfully!', 'allTours' => $allTours]);
        }
        Session::flash('status', 'error');
        Session::flash('msg', 'Tour Creation Failed, please try again!');
        return response(['status' => false, 'msg' => 'Tour Creation Failed, please try again!']);
    }

    /**
     * @param String $routeName
     * @param String $fileName
     */
    public function createNewTour(string $routeName, string $fileName) {

        $tourCreation = new Tourifies();
        $tourCreation->route = $routeName;
        $tourCreation->file_name = $fileName.'.js';
        $tourCreation->save();

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param $tour
     * @return void
     */
    public function edit($tour)
    {

        $tour = Tourifies::find($tour);
        $regex = "/\{.*\}/s";
        $tourFile = Storage::get('public/tourify/'.$tour->file_name);
        if($tourFile){

            preg_match($regex, $tourFile, $matchesAll);
            $allTour = json_decode($matchesAll[0], true);
            $allTourSteps = $allTour['steps'];
            return view('Tourify::edit', compact('tour', 'allTourSteps'));

        }

        return back()->with('msg', "Oops! Tour file not found. Did you delete it manually from the storage?")
                     ->with('status','error');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $tour
     * @return void
     */
    public function update(Request $request, $tour)
    {
        //
        $tour = Tourifies::find($tour);
        $allTourData = $request->input('tour');
        $parsedTour = json_decode($allTourData, true);
        Storage::delete('public/tourify/'.$tour->file_name);
        $newFile = Storage::put('public/tourify/'.$parsedTour['id'].'.js', 'let tour = '.$allTourData.';hopscotch.startTour(tour);');

        if($newFile){

            self::updateTour($tour->id, $request->input('route'), $parsedTour['id']);
            Artisan::call('view:clear');

            $allTours = Tourifies::selectRaw('route as `route`, file_name as `fileName`, id as `id`, created_at as `created_at`,updated_at as `updated_at`')->get();
            Session::flash('msg', 'Tour Updated Successfully!');
            Session::flash('status', 'success');
            return response(['status' => true, 'msg' => 'Tour Updated Successfully!', 'allTours' => $allTours]);
        }
        Session::flash('status', 'error');
        return response(['status' => false, 'msg' => 'Tour Updating Failed, please try again!']);
    }

    /**
     * @param int $id
     * @param String $routeName
     * @param String $fileName
     */
    public function updateTour (int $id, string $routeName, string $fileName) {

        $tourCreation = Tourifies::find($id);
        $tourCreation->route = $routeName;
        $tourCreation->file_name = $fileName.'.js';
        $tourCreation->save();

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Tourifies $tour
     * @return void
     */
    public function destroy($tour)
    {
        //
        $tour = Tourifies::find($tour);
        $status = Storage::delete('public/tourify/'.$tour->file_name);
        if($status){

            $deleteRow = Tourifies::destroy($tour->id);
            if($deleteRow){
                return back()->with('msg', "Delete Successful")
                            ->with('status', "success");
            }
            return back()->with('msg', "Oops! The tour id not found in the database, did you manually delete the row from table?")
                         ->with('status', "error");
        }
        return back()->with('msg', "Oops! Tour file not found. Did you delete it manually from the storage?")
            ->with('status', "error");
    }
}
