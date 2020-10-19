<?php

namespace App\Export;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


use App\Models\Setupsikd\Tmrekening_akun_kelompok;

use Illuminate\Http\Request;

use App\Models\Setupsikd\Tmrekening_akun_kelompok_jenis;
use App\Models\Setupsikd\Tmrekening_akun_kelompok_jenis_objek;
use App\Models\Setupsikd\Tmrekening_akun_kelompok_jenis_objek_rincian;
use App\Helpers\Properti_app;
use App\Libraries\Jasper_report;
use App\Libraries\List_pendapatan;
use App\Models\Setupsikd\Tmrekening_akun_kelompok_jenis_objek_rincian_sub;
use App\Models\Sikd_satker;
use App\Models\Tmpendapatan;
use App\Models\TmpendapatantargetModel;
use App\Models\Tmopd;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Maatwebsite\Excel\Concerns\WithProperties;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Exportpendapatanbulan implements ShouldAutoSize, FromView, WithEvents, WithStyles
{

    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $tahun_id          = $this->request['tahun_id'];
        $tmsikd_satker_id  = $this->request['tmsikd_satker_id'];
        $dari              = $this->request['dari'];
        $sampai            = $this->request['sampai'];

        $tahun             = Properti_app::tahun_sekarang();
        $listarget         = new TmpendapatantargetModel;

        $akun_kelompok        = Tmrekening_akun_kelompok::get();
        $kelompok_jenis       = new Tmrekening_akun_kelompok_jenis;
        $kelompok_object      = new Tmrekening_akun_kelompok_jenis_objek;
        $kelompok_rincian     = new Tmrekening_akun_kelompok_jenis_objek_rincian;
        $kelompok_sub_rincian = new Tmrekening_akun_kelompok_jenis_objek_rincian_sub;
        $tmpendapatan         = new Tmpendapatan;

        $opd                  = Sikd_satker::find($this->request['tmsikd_satker_id']);
        //get jumlah data per rek obj 
        $tbyrincian     = Tmpendapatan::with('Tmrekening_akun_kelompok_jenis_objek_rincian');
        $tbyrincian_sub = Tmpendapatan::with('Tmrekening_akun_kelompok_jenis_objek_rincian_sub');

        $total_pad = Tmpendapatan::select(\DB::raw('sum(jumlah) as total_pad'))->where(['tahun' => $tahun])->first();
        return view('laporan_pendapatan.rekapbulanexcel', [
            'tahun' => $tahun,
            'dari' => $dari,
            'opd' => $opd,
            'tmsikd_satker_id' => $tmsikd_satker_id,
            'tahun_id' => $tahun_id,
            'sampai' => $sampai,
            'listarget' => $listarget,
            'tmpendapatan' => $tmpendapatan,
            // 'jenisobject' => $jenisobject,
            // 'objectrincian' => $objectrincian,
            // 'objectrinciansub' => $objectrinciansub,
            // get list rekening pendapatan
            'total_pad' => $total_pad,
            'tbyrincian' => $tbyrincian,
            'tbyrincian_sub' => $tbyrincian_sub,

            'akun_kelompok' => $akun_kelompok,
            'kelompok_jenis' => $kelompok_jenis,
            'kelompok_object' => $kelompok_object,
            'kelompok_rincian' => $kelompok_rincian,
            'kelompok_sub_rincian' => $kelompok_sub_rincian,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class  => function (BeforeExport $event) {
                $event->writer->setCreator('Ismarianto');
            },
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

                $event->sheet->getStyle('A1:R54')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('B2')->getFont()->setBold(true);
    }
}
