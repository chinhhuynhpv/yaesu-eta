<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class OperatorLineListExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $userId;

    function __construct($userId) {
            $this->userId = $userId;
    }
    
    public function collection()
    {
        
        $query = DB::table('m_user_line_talk_group as ltg')
                    ->join('m_user_lines as ul', 'ltg.line_id', 'ul.id')
                    ->join('m_user_talk_groups as tg', 'ltg.group_id', 'tg.id')
                    ->join('m_models', 'm_models.id', 'ul.models_id')
                    ->where('ltg.deleted_at', null);
        if(!empty($this->userId)) {
            $query = $query->where('ltg.user_id', $this->userId);
        }

        $lineTalkGroups = $query->orderBy('ltg.line_id', 'asc')
                                ->orderBy( 'ltg.number', 'asc')
                                ->select('ul.voip_line_id','ul.voip_id_name', 'ul.voip_line_password',
                                         'ul.priority', 'tg.voip_group_id', 'm_models.product_name', 'ul.recording',
                                         'ul.commander', 'ul.individual', 'ul.individual_priority', 'ul.gps', 'ul.video', 'ul.cue_reception', 'ltg.number', 'ul.sim_num')
                                ->get();  
        
        $data = $this->customeDataExport($lineTalkGroups);
        $executeLineTalkGroups = collect($this->executeDateColumns($data));
    
        return $executeLineTalkGroups;
    }

    public function executeDateColumns($lineTalkGroups) {
        
        $selectGroup = [];  
        $listLines = [];

        foreach( $lineTalkGroups as $ltg ) {
            if($ltg->number == 1) {
                $key = count($listLines);
                $listLines[$key]['line_id'] = $ltg->voip_line_id;
                $listLines[$key]['voip_id_name'] = $ltg->voip_id_name;
                $listLines[$key]['voip_line_password'] = $ltg->voip_line_password;
                $listLines[$key]['phone'] = '';
                $listLines[$key]['priority'] = $ltg->priority;
                $listLines[$key]['select_group'] = $this->getTheSelectGroup( $ltg->voip_line_id, $lineTalkGroups);
                $listLines[$key]['voip_group_id'] = $ltg->voip_group_id;
                $listLines[$key]['product_name'] = $ltg->product_name;
                $userFunction = [
                                    'recording' => $ltg->recording,
                                    'commander' => $ltg->commander,
                                    'individual' => $ltg->individual,
                                    'individual_priority' => $ltg->individual_priority,
                                    'gps' => $ltg->gps,
                                    'video' => $ltg->video,
                                    'cue_reception' => $ltg->cue_reception
                                ];
                $listLines[$key]['user_function'] = $this->createUserFunctionColumns( $userFunction);
                $listLines[$key]['blank1'] = '';
                $listLines[$key]['blank2'] = '';
                $listLines[$key]['sim_num'] = $ltg->sim_num;
                $listLines[$key]['user_type'] = '0';
                $listLines[$key]['sim_expiration_date'] = '2020-06-01';
                $listLines[$key]['set_app_password'] = '';
                $listLines[$key]['enable_app_password'] = '1';
                // set the other columns 
                
            }
        }
        
        return $listLines;
    }

    public function createUserFunctionColumns($userFunctionData) {
        return implode(',', array_filter($userFunctionData));
    }

    public function getTheSelectGroup($voipLineId, $listLines) {
        
        $selectGroup = [];
        foreach($listLines as $item) {
            if($item->voip_line_id == $voipLineId && $item->number != 1) {
                $selectGroup[count($selectGroup)] = $item->voip_group_id;
            }
        }

        return implode(',',$selectGroup);
    }

    public function customeDataExport($lineTalkGroups) {
      
        foreach( $lineTalkGroups as $ltg) {
            
            if($ltg->recording == '1') {
                $ltg->recording = 3;
            }else{
                $ltg->recording = '';
            }

            if($ltg->commander == '1') {
                $ltg->commander = 6;
            }else{
                $ltg->commander = '';
            }

            if($ltg->individual == '1') {
                $ltg->individual = 7;
            }else{
                $ltg->individual = '';
            }

            if($ltg->individual_priority == '1') {
                $ltg->individual_priority = 11;
            }else{
                $ltg->individual_priority = '';
            }

            if($ltg->gps == '1') {
                $ltg->gps = 12;
            }else{
                $ltg->gps = '';
            }

            if($ltg->video == '1') {
                $ltg->video = 16;
            }else{
                $ltg->video = '';
            }

            if($ltg->cue_reception == '1') {
                $ltg->cue_reception = 19;
            }else{
                $ltg->cue_reception = '';
            }
        }
        return $lineTalkGroups;
    }

    public function headings(): array
    {
        return [
            'Line Id',
            'Username',
            'Line password',
            'Phone',
            'Priority',
            'Select Group 1 ~',
            'Main Group',
            'Model Name',
            'User Function',
            'IMEI Format: Machine Type-IMEI',
            'EncryptionType 1-RSA / AES 2-SM2 / SM4',
            'Sim number',
            'User Type',
            'SIM Card Expiration Time Format: yyyy-MM-dd',
            'Set app password',
            'Whether to enable the app password',
        ];
    }
}
