<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chart extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
        $chartData=file_get_contents('assets/wanita.json');
        $chartData=json_decode($chartData);
        $res=array();
        foreach($chartData as $row)
        {
            $dat=[$row->tahun,(double)$row->val];
            array_push($res,$dat);
        }
        // echo json_encode($res);
        $data['PieChartTitle']='Jumlah Penduduk Wanita Tidak/Belum Sekolah di Kabupaten Purworejo';
        $data['PieChartData']=json_encode($res);
        $this->load->view('grafik', $data);
	}

    function people()
    {
        //people data
        $source=file_get_contents('assets/people.json');
        $source=json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $source), true );  
        $result=array();
        foreach($source as $row)
        {
            if(!isset($result[$row['gender']]))
            {
                $result[$row['gender']]=array($row['age']);
            }else{
                array_push($result[$row['gender']], $row['age']);
            }
        }
        $keys=array_keys($result);
        $people=array();
        foreach ($keys as $row)
        {
            $people[]=[$row, count($result[$row])];
        }
        $data['PieChartData']=json_encode($people);
        $data['PieChartTitle']='Data Orang';

        //line chart
        $line=[array('NAMA', 'UMUR')];
        foreach($source as $row)
        {
            $dat=array($row['firstName'], (double)$row['age']);
            array_push($line, $dat);
        }
        $data['LineChartData']=json_encode($line);
        $data['LineChartTitle']='Umur';

        //bar chart
        // $bar=[array('TAHUN', 'JUMLAH')];
        // foreach($source as $row)
        // {
        //     $year=date('Y', strortime($row['birth']));
        //     $month=dtae('n', strtime($row['birth']));
        //     if($year)
        // }
        $this->load->view('grafik', $data);
        // echo json_encode(array_keys($result));
    }

    function ikan()
    {
        //people data
        $source=file_get_contents('assets/ikan.json');
        $source=json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $source), true );  
        $result=array();
        foreach($source as $row)
        {
            if(!isset($result[$row['jenis_budidaya']]))
            {
                $result[$row['jenis_budidaya']]=array($row['jenis_ikan']);
            }else{
                array_push($result[$row['jenis_budidaya']], $row['jenis_ikan']);
            }
        }
        $keys=array_keys($result);
        $people=array();
        foreach ($keys as $row)
        {
            $people[]=[$row, count($result[$row])];
        }
        $data['PieChartData']=json_encode($people);
        $data['PieChartTitle']='Data Jenis Budidaya';

        //line chart
        $line=[array('JENIS IKAN', 'PRODUKSI')];
        foreach($source as $row)
        {
            // $dat=array($row['jenis_ikan'], (double)$row['produksi']);
            // array_push($line, $dat);
            $year=($row['tahun']);
            if($year=='2018')
            {
                $dat=array($row['jenis_ikan'], (double)$row['produksi']);
                array_push($line, $dat);
            }
        }
        $data['LineChartData']=json_encode($line);
        $data['LineChartTitle']='Data Produksi Ikan Tahun 2019';

        //bar chart
        $bar=[array('JENIS IKAN', 'PRODUKSI 2018', 'PRODUKSI 2019')];
        foreach($source as $row)
        {
            $year=($row['tahun']);
            $ikan=($row['jenis_ikan']);
            if($year=='2018')
            {
                if(!isset($totalData['2018'][$ikan]))
                {
                    $totalData['2018'][$ikan]=$row['produksi'];
                }else{
                    array_push($totalData['2018'][$ikan],$row['produksi']);
                }
            }
            if($year=='2019')
            {
                if(!isset($totalData['2019'][$ikan]))
                {
                    $totalData['2019'][$ikan]=$row['produksi'];
                }else{
                    array_push($totalData['2019'][$ikan],$row['produksi']);
                }
            }
        }
        $ikan=array_keys($totalData['2018']);
        foreach(array_keys($totalData['2018']) as $row)
        {
            $dat=[$row, ($totalData['2018'][$row]), ($totalData['2019'][$row])];
            array_push($bar, $dat);
        }
        $data['BarChartData']=json_encode($bar);
        $data['BarChartTitle']='Perbandingan Produksi Ikan Tahun 2018 dan 2019';
        $this->load->view('grafik', $data);
        // echo json_encode($bar);
    }
}