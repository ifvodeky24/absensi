<?php
namespace app\controllers;
use app\models\TbAbsensi;
use Mpdf\Mpdf;
use Yii;
use yii\db\Query;
use yii\web\Controller;

class LaporanController extends Controller
{
    public function actionIndex() {
        $model = new \app\models\TbAbsensi();
        $post = Yii::$app->request->post();

        if ($model->load($post)) {
            $bulanAwal = $post['TbAbsensi']['bulan_awal'];
            $bulanAkhir = $post['TbAbsensi']['bulan_akhir'];

            return $this->redirect(['laporan', 'awal' => $bulanAwal, 'akhir' => $bulanAkhir]);
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionLaporan() {
        $model = new TbAbsensi();

        $tgl_awal = Yii::$app->getRequest()->getQueryParam('awal');
        $tgl_akhir = Yii::$app->getRequest()->getQueryParam('akhir');

        $dataAbsensiMasuk = (new Query());
        $dataAbsensiMasuk->select(['tb_absensi.*', 'tb_master_status_absensi.status_absensi'])
        ->from('tb_absensi')
        ->leftJoin('tb_master_status_absensi', 'tb_master_status_absensi.id_status_absensi = tb_absensi.status_absensi_id')
        ->where('tb_absensi.jenis_absensi="masuk" AND tb_absensi.date_absensi between "'.$tgl_awal.'" AND "'.$tgl_akhir.'" ');

        $dataAbsensiKeluar = (new Query());
        $dataAbsensiKeluar->select(['tb_absensi.*', 'tb_master_status_absensi.status_absensi'])
        ->from('tb_absensi')
        ->leftJoin('tb_master_status_absensi', 'tb_master_status_absensi.id_status_absensi = tb_absensi.status_absensi_id')
        ->where('tb_absensi.jenis_absensi="keluar" AND tb_absensi.date_absensi between "'.$tgl_awal.'" AND "'.$tgl_akhir.'" ');

        $commandAbsensiMasuk = $dataAbsensiMasuk->createCommand();
        $modelAbsensiMasuk = $commandAbsensiMasuk->queryAll();

        $commandAbsensiKeluar = $dataAbsensiKeluar->createCommand();
        $modelAbsensiKeluar = $commandAbsensiKeluar->queryAll();

        $mpdf = new Mpdf();
        $mpdf->SetTitle("Laporan");
        $stylesheet = file_get_contents('http://localhost/absensi/web/css/reportstyles.css');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($this->renderPartial('laporan', [
            'model' => $model,
            'model_absensi_masuk' => $modelAbsensiMasuk,
            'model_absensi_keluar' => $modelAbsensiKeluar,
        ]));
        $mpdf->Output('laporan.pdf', 'I');
        exit();
    }
}