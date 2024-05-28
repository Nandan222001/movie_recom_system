<?php
namespace App\Http\Repository\Organizations\Designers\Designs;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
DesignModel
};
use Config;

class DesignsRepository  {


    public function getAll(){
        try {
            $data_output= DesignModel::get();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function addAll($request)
    {   
        try {
           //insert data to users table
           
            $dataOutput = new DesignModel();
            $dataOutput->design_name = $request->design_name;
            $dataOutput->design_page = $request->design_page;
            $dataOutput->project_name = $request->project_name;
            $dataOutput->time_allocation=$request->time_allocation;
            $dataOutput->image = 'null';
            $dataOutput->save();
            $last_insert_id = $dataOutput->id;
            $imageName = $last_insert_id . '_' . rand(100000, 999999) . '_image.' . $request->image->extension();

            $finalOutput = DesignModel::find($last_insert_id);
            $finalOutput->image = $imageName;
            $finalOutput->save();

            return [
                'ImageName' => $imageName,
                'status' => 'success'
            ];

        } catch (\Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }
    public function getById($id){
    try {
            $dataOutputByid = DesignModel::find($id);
            if ($dataOutputByid) {
                return $dataOutputByid;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }

        public function updateAll($request){
        try { 
       
            $dataOutput = DesignModel::find($request->id);

            if (!$dataOutput) {
                return [
                    'msg' => 'Update Data not found.',
                    'status' => 'error'
                ];
            }

            $previousEnglishImage = $dataOutput->image;
            $dataOutput->design_name = $request->design_name;
            $dataOutput->design_page = $request->design_page;
            $dataOutput->project_name = $request->project_name;
            $dataOutput->time_allocation=$request->time_allocation;
            

            $dataOutput->save();
            $last_insert_id = $dataOutput->id;

            $return_data['last_insert_id'] = $last_insert_id;
            $return_data['image'] = $previousEnglishImage;
            return  $return_data;
        
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to Update Data.',
                'status' => 'error',
                'error' => $e->getMessage() // Return the error message for debugging purposes
            ];
        }
    }


    public function deleteById($id){
            try {
                $deleteDataById = DesignModel::find($id);
                
                if ($deleteDataById) {
                    if (file_exists_view(Config::get('DocumentConstant.DESIGNS_DELETE') . $deleteDataById->image)){
                        removeImage(Config::get('DocumentConstant.DESIGNS_DELETE') . $deleteDataById->image);
                    }
                    $deleteDataById->delete();
                    
                    return $deleteDataById;
                } else {
                    return null;
                }
            } catch (\Exception $e) {
                return $e;
            }
    }

}