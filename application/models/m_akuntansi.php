<?php

class M_akuntansi extends CI_Model {
    
    function delete_bukubesar($id) {
        $this->db->delete('jurnal', array('id' => $id));
    }
    
    function neraca_load_data($jenis) {
        $sql = "select * from rekening where nama = '$jenis'";
        return $this->db->query($sql);
    }
    
    function data_subrekening_load_data($id = null, $id_rekening = null) {
        $q = null;
        if ($id != null) {
            $q.=" and id = '$id'";
        }
        if ($id_rekening != null) {
            $q.=" and id_parent = '$id_rekening'";
        }
        $sql = "select * from rekening where id_parent is not NULL $q order by id";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function data_subsubrekening_load_data($id_parent) {
        $sql = "select * from rekening where id_parent = '$id_parent'";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function get_total_jurnal_by_subsub_aset($id) {
        // and date(j.waktu) between '$awal' and '$akhir'
        // $awal = date2mysql(get_safe('awal'));
        /// $akhir= date2mysql(get_safe('akhir'));
        $sql = "select sum(j.debet) as total, sum(j.kredit) as total_kredit from jurnal j
            join rekening ssssr on (ssssr.id = j.id_rekening)
            join rekening sssr on (sssr.id = ssssr.id_parent or sssr.id = j.id_rekening)
            join rekening ssr on (ssr.id = sssr.id_parent)
            join rekening sr on (sr.id = ssr.id_parent)
            join rekening r on (r.id = sr.id_parent)
            where ssr.id = '$id'";
        //echo $sql."<br/>";
        return $this->db->query($sql);
    }
    
    function get_total_penyusutan_by_subsub_aset($id_sub_sub_sub) {
//        $awal = date2mysql(get_safe('awal'));
//        $akhir= date2mysql(get_safe('akhir'));
        // and date(j.waktu) between '$awal' and '$akhir'
        $sql = "select (sum(j.debet)-sum(j.kredit)) as total, sum(j.debet) as total_debet, sum(j.kredit) as total_kredit 
            from jurnal j
            join rekening ssr on (ssr.id = j.id_rekening)
            join rekening sr on (sr.id = ssr.id_parent)
            where sr.id = '$id_sub_sub_sub' and ssr.nama REGEXP 'cadangan|akumulasi'";
        //echo $sql."<br/>";
        return $this->db->query($sql);
    }
    
    function total_jurnal_by_sub_sub($id_sub_sub) {
        $sql = "select (sum(j.debet)-sum(j.kredit)) as total, sum(j.debet) as total_debet, sum(j.kredit) as total_kredit 
            from jurnal j
            join rekening ssr on (ssr.id = j.id_rekening)
            join rekening sr on (sr.id = ssr.id_parent)
            where sr.id = '$id_sub_sub'";
        //echo $sql."<br/>";
        return $this->db->query($sql);
    }
    
    function save_jurnal() {
        $jenis = post_safe('jenis');
        $nilai = currencyToNumber(post_safe('nilai'));
        $rek   = post_safe('rekening');
        if ($jenis === 'Debet') {
            save_jurnal_debet('', NULL, $nilai, NULL, NULL, $rek);
        } else {
            save_jurnal_kredit('', NULL, $nilai, NULL, NULL, $rek);
        }
        return TRUE;
    }
    
    function save_sub_sub_rekening() {
        $data_sub_sub = array(
            'id' => post_safe('kode_subsub'),
            'id_sub_rekening' => post_safe('id_subrekening'),
            'nama' => post_safe('sub_sub_rekening')
        );
        $this->db->insert('sub_sub_rekening', $data_sub_sub);
        $result['id'] = $this->db->insert_id();
        $result['status'] = TRUE;
        return $result;
    }
    
    function save_edit_sub_sub_rekening() {
        $data_sub_sub = array(
            'id' => post_safe('kode_subsub'),
            'id_sub_rekening' => post_safe('id_subrekening'),
            'nama' => post_safe('sub_sub_rekening')
        );
        $this->db->where('id', post_safe('id_sub_sub_reks'));
        $this->db->update('sub_sub_rekening', $data_sub_sub);
        $result['id'] = post_safe('id_sub_sub_reks');
        $result['status'] = TRUE;
        return $result;
    }
    
    function rekening_save() {
        $this->db->insert('rekening', array('id' => post_safe('kode_rek'), 'nama' => post_safe('nama_rekening'), 'urut' => post_safe('kode_rek')));
        return $this->db->insert_id();
    }
    
    function rekening_update() {
        $data = array(
            'id' => post_safe('kode_rek'),
            'nama' => post_safe('nama_rekening')
        );
        $this->db->where('id', post_safe('kode_rek_id'));
        $this->db->update('rekening', $data);
    }
    
    function subrekening_edit() {
        $data = array(
            'id' => post_safe('kode_sub_rek'),
            'id_rekening' => post_safe('rekening_id'),
            'nama' => post_safe('nama_sub')
        );
        $this->db->where('id', post_safe('kode_sub_rek_id'));
        $this->db->update('sub_rekening', $data);
    }
    
    function subrekening_save() {
        $this->db->insert('sub_rekening', array('id' => post_safe('kode_sub_rek'), 'id_rekening' => post_safe('rekening_id'),'nama' => post_safe('nama_sub')));
        return $this->db->insert_id();
    }
    
    function delete_rekening($id) {
        $this->db->delete('rekening', array('id' => $id));
    }
    
    function delete_subrekening($id) {
        $this->db->delete('sub_rekening', array('id' => $id));
    }
    
    function delete_subsubrekening($id) {
        $this->db->delete('sub_sub_rekening', array('id' => $id));
    }
    
    function referensi_load_data() {
        $sql = "select r.nama as rekening, s.nama as sub_rekening, ss.id, ss.nama as sub_sub_rekening from rekening r 
            join sub_rekening s on (r.id = s.id_rekening)
            join sub_sub_rekening ss on (s.id = ss.id_sub_rekening)";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function list_bukubesar($search) {
        $q = null;
        if ($search['awal'] !== '') {
            $q.=" and date(j.waktu) between '".$search['awal']."' and '".$search['akhir']."'";
        }
        $sql = "select j.*, r.nama as rekening, IFNULL(j.transaksi,'-') as transaksi, date(j.waktu) as tanggal, r.kode from jurnal j
            join rekening r on (j.id_rekening = r.id)
            where j.id is not NULL $q order by j.waktu desc";
        return $this->db->query($sql);
    }
    
    function lap_shu_load_data($year) {
        $sql = "select r.nama as rekening, s.nama as sub_rekening, j.* from jurnal j
            join sub_sub_sub_sub_rekening ssssr on (j.id_sub_sub_sub_sub_rekening = ssssr.id)
            join sub_sub_sub_rekening sssr on (ssssr.id_sub_sub_sub_rekening = sssr.id)
            join sub_sub_rekening ss on (sssr.id_sub_sub_rekening = ss.id) 
            join sub_rekening s on (ss.id_sub_rekening = s.id)
            join rekening r on (s.id_rekening = r.id) where year(j.waktu) = '$year'";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function jurnal_load_data_by_subrekening($id_subrekening = null, $id_rekening = null) {
        $q = null;
        if ($id_subrekening != null) {
            $q = "where s.id = '$id_subrekening'";
        }
        if ($id_rekening != null) {
            $q = "where r.id = '$id_rekening'";
        }
        $sql = "select sum(j.debet) as debet, sum(j.kredit) as kredit from jurnal j
            join sub_sub_sub_sub_rekening ssssr on (j.id_sub_sub_sub_sub_rekening = ssssr.id)
            join sub_sub_sub_rekening sssr on (ssssr.id_sub_sub_sub_rekening = sssr.id)
            join sub_sub_rekening ss on (j.id_sub_sub_sub_sub_rekening = ss.id)
            join sub_rekening s on (ss.id_sub_rekening = s.id)
            join rekening r on (s.id_rekening = r.id)
            $q";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function sub_sub_rek_save() {
        $cek = $this->db->query("select count(*) as jumlah from sub_sub_rekening where id = '".post_safe('kode_sub_sub_rek')."'")->row();
        $array = array(
            'id' => post_safe('kode_sub_sub_rek'),
            'id_sub_rekening' => post_safe('sub_rekening_id'),
            'nama' => post_safe('nama_sub_sub')
        );
        if ($cek->jumlah == 0) {
            $this->db->insert('sub_sub_rekening', $array);
            return $this->data_subsubrekening_load_data($this->db->insert_id())->row();
        } else {
            $this->db->where('id', post_safe('kode_sub_sub_rek'));
            $this->db->update('sub_sub_rekening', $array);
            return $this->data_subsubrekening_load_data(post_safe('kode_sub_sub_rek'))->row();
        }
    }
    
    function sub_sub_sub_rek_save() {
        if (post_safe('id_sub_sub_sub') == '') {
            $array = array(
                'id' => post_safe('kode'),
                'id_sub_sub_rekening' => post_safe('sub_sub_rek_id'),
                'nama' => post_safe('nama')
            );
            $this->db->insert('sub_sub_sub_rekening', $array);
            $id = $this->db->insert_id();
        } else {
            $array = array(
                'id' => post_safe('kode'),
                'id_sub_sub_rekening' => post_safe('sub_sub_rek_id'),
                'nama' => post_safe('nama')
            );
            $this->db->where('id', post_safe('id_sub_sub_sub'));
            $this->db->update('sub_sub_sub_rekening', $array);
            $id = post_safe('id_sub_sub_sub');
        }
        return $this->data_subsubsub_rekening_load_data($id)->row();
    }
    
    function save_sub_sub_sub_sub_rekening() {
        $array = array(
            'id' => post_safe('kode_subsubsubsub'),
            'id_sub_sub_sub_rekening' => post_safe('sub_sub_sub_rekening'),
            'nama' => post_safe('nama_ssss')
        );
        $this->db->insert('sub_sub_sub_sub_rekening',$array);
        return $this->data_subsubsubsub_rekening_load_data($this->db->insert_id())->row();
    }
    
    function save_edit_sub_sub_sub_sub_rekening() {
        $array = array(
            'id' => post_safe('kode_subsubsubsub'),
            'id_sub_sub_sub_rekening' => post_safe('sub_sub_sub_rekening'),
            'nama' => post_safe('nama_ssss')
        );
        $this->db->where('id', post_safe('kode_subsubsubsub'));
        $this->db->update('sub_sub_sub_sub_rekening',$array);
        return $this->data_subsubsubsub_rekening_load_data(post_safe('kode_subsubsubsub'))->row();
    }
    
    function get_sub_sub_sub_sub_rekening($q) {
        $sql = "select r.id as id_rekening, sr.id as id_sub_rekening, ssr.id as id_sub_sub_rekening, sssr.id as id_sub_sub_sub_rekening, ssssr.id as id_sub_sub_sub_sub_rekening,
            concat(ssssr.nama,' ',sssr.nama,' ',ssr.nama,' ',sr.nama,' ',r.nama) as nama from sub_sub_sub_sub_rekening ssssr
            join sub_sub_sub_rekening sssr on (ssssr.id_sub_sub_sub_rekening = sssr.id)
            join sub_sub_rekening ssr on (sssr.id_sub_sub_rekening = ssr.id)
            join sub_rekening sr on (ssr.id_sub_rekening = sr.id)
            join rekening r on (r.id = sr.id_rekening)
            where ssssr.nama like ('%$q%') order by locate('$q',ssssr.nama)";
        return $this->db->query($sql);
    }
    
    function data_rekening_dakwah($nama) {
        $q = " where year(j.waktu) between '".date("Y-m-d")."' and '".date("Y-m-d")."'";
        if (isset($_GET['awal'])) {
            $q =" where year(j.waktu) between '".  date2mysql(get_safe('awal'))."' and  '".  date2mysql(get_safe('akhir'))."'";
        }
        $sql = "select sum(j.nilai) as total, r.nama as rekening, s.nama as sub_rekening, j.* from jurnal j
            join sub_sub_sub_sub_rekening ssssr on (j.id_sub_sub_sub_sub_rekening = ssssr.id)
            join sub_sub_sub_rekening sssr on (ssssr.id_sub_sub_sub_rekening = sssr.id)
            join sub_sub_rekening ss on (sssr.id_sub_sub_rekening = ss.id) 
            join sub_rekening s on (ss.id_sub_rekening = s.id)
            join rekening r on (s.id_rekening = r.id) $q and r.nama = '$nama'";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function get_total_jurnal_by_subsub($id_sub_sub) {
        $awal = date2mysql(get_safe('awal'));
        $akhir= date2mysql(get_safe('akhir'));
        $sql = "select (sum(j.debet)-sum(j.kredit)) as total from jurnal j
            join sub_sub_sub_sub_rekening ssssr on (ssssr.id = j.id_sub_sub_sub_sub_rekening)
            join sub_sub_sub_rekening sssr on (sssr.id = ssssr.id_sub_sub_sub_rekening)
            join sub_sub_rekening ssr on (ssr.id = sssr.id_sub_sub_rekening)
            where ssr.id = '$id_sub_sub' and date(j.waktu) between '$awal' and '$akhir'";
        //echo $sql."<br/>";
        return $this->db->query($sql);
    }
    
    function get_sub_sub_rek_auto($q) {
        $sql = "select ssr.id, concat(ssr.nama,' <br/> ', sr.nama, ' - ', r.nama) as ssrekening, ssr.nama from sub_sub_rekening ssr 
            join sub_rekening sr on (ssr.id_sub_rekening = sr.id)
            join rekening r on (sr.id_rekening = r.id)
            where ssr.nama like ('%$q%') order by locate ('$q', ssr.nama)
            ";
        return $this->db->query($sql);
    }
    
    function get_sub_rek_auto($q) {
        $sql = "select sr.id, concat(sr.nama,' <br/> ', r.nama) as srekening, sr.nama from sub_rekening sr
            join rekening r on (sr.id_rekening = r.id)
            where sr.nama like ('%$q%') order by locate ('$q', sr.nama)";
        return $this->db->query($sql);
    }
    
    function set_awal_neraca_save() {
        if (post_safe('jeniss') == 'debet') {
            
            if (post_safe('id_jurnal') === '') {
                $data = array(
                    'waktu' => date("Y-m-d H:i:s"),
                    'jenis_transaksi' => post_safe('jenis_trans'),
                    'id_sub_sub_sub_sub_rekening' => post_safe('id_rek'),
                    'debet' => currencyToNumber(post_safe('nilai')),
                    'kredit' => '0'
                );
                
                $this->db->insert('jurnal', $data);
                $id = $this->db->insert_id();
            } else {
                $data = array(
                    'jenis_transaksi' => post_safe('jenis_trans'),
                    'id_sub_sub_sub_sub_rekening' => post_safe('id_rek'),
                    'debet' => currencyToNumber(post_safe('nilai')),
                    'kredit' => '0'
                );
                $this->db->where('id', post_safe('id_jurnal'));
                $this->db->update('jurnal', $data);
                $id = post_safe('id_jurnal');
            }
        } else {
            
            if (post_safe('id_jurnal') === '') {
                $data2 = array(
                    'waktu' => date("Y-m-d H:i:s"),
                    'jenis_transaksi' => post_safe('jenis_trans'),
                    'id_sub_sub_sub_sub_rekening' => post_safe('id_rek'),
                    'kredit' => currencyToNumber(post_safe('nilai')),
                    'debet' => '0'
                );
                $this->db->insert('jurnal', $data2);
                $id = $this->db->insert_id();
            } else {
                $data2 = array(
                    'jenis_transaksi' => post_safe('jenis_trans'),
                    'id_sub_sub_sub_sub_rekening' => post_safe('id_rek'),
                    'kredit' => currencyToNumber(post_safe('nilai')),
                    'debet' => '0'
                );
                $this->db->where('id', post_safe('id_jurnal'));
                $this->db->update('jurnal', $data2);
                $id = post_safe('id_jurnal');
            }
        }
        $result['status'] = TRUE;
        $result['id'] = $id;
        return $result;
    }

    function kode_rekening_load_data($limit, $start, $param){
        $q = '';
        $paging = " limit " . $start . "," . $limit . " ";
        
        if ($param['tarif'] != '') {
            $q.=" and l.nama like '%".$param['tarif']."%' ";
        }
        if ($param['debet'] != '') {
            $q.=" and kt.kode_debet = '".$param['debet']."'";
        }
      
        if ($param['kredit'] != '') {
            $q.=" and kt.kode_kredit = '".$param['kredit']."'";
        }

        if ($param['jenis_layan'] != '') {
            $q.=" and kt.jenis_pelayanan = '".$param['jenis_layan']."'";
        }

        if ($param['id'] != ''){
            $q = " and kt.id = '".$param['id']."' ";
        }
    
        $db = "select kt.*, kt.id as id_kode, l.nama as tarif, db.nama as debet, kr.nama as kredit,
                u.nama as unit, t.*, p.nama as profesi, j.nama as jurusan 
               from kode_rekening_tarif kt
               join tarif t on (t.id = kt.tarif_id)
               left join profesi p on (t.id_profesi = p.id)
               left join jurusan_kualifikasi_pendidikan j on (t.id_jurusan_kualifikasi_pendidikan = j.id)
               left join unit u on (t.id_unit = u.id)
               join layanan l on (t.id_layanan = l.id)
               left join sub_sub_sub_sub_rekening db on (db.id = kt.kode_debet)
               left join sub_sub_sub_sub_rekening kr on (kr.id = kt.kode_kredit)
               where kt.id is not null $q order by l.nama, p.nama, j.nama, u.nama, t.bobot,t.kelas";
        //echo $db.$paging;
        $data = $this->db->query($db . $paging);
        $ret['data'] = $data->result();
        $ret['jumlah'] = $this->db->query($db)->num_rows;
        return $ret;
    }

    function get_kode_rekening($id){
        $db = "select kt.*, kt.id as id_kode, l.nama as tarif, db.nama as debet, kr.nama as kredit,
                u.nama as unit, t.*, p.nama as profesi, j.nama as jurusan 
               from kode_rekening_tarif kt
               join tarif t on (t.id = kt.tarif_id)
               left join profesi p on (t.id_profesi = p.id)
               left join jurusan_kualifikasi_pendidikan j on (t.id_jurusan_kualifikasi_pendidikan = j.id)
               left join unit u on (t.id_unit = u.id)
               join layanan l on (t.id_layanan = l.id)
               left join sub_sub_sub_sub_rekening db on (db.id = kt.kode_debet)
               left join sub_sub_sub_sub_rekening kr on (kr.id = kt.kode_kredit)
               where kt.id = '$id' ";
       
        return $this->db->query($db)->row();
    }

    function kode_rekening_update(){
        $data = array(
            'tarif_id' => (post_safe('id_tarif') != '')?post_safe('id_tarif'):NULL,
            'jenis_pelayanan' => (post_safe('jenis_layan') != '')?post_safe('jenis_layan'):NULL,
            'kode_debet' => (post_safe('id_debet') != '')?post_safe('id_debet'):NULL,
            'kode_kredit' => (post_safe('id_kredit') != '')?post_safe('id_kredit'):NULL
        );

        $id = post_safe('id_kode_rekening');

        if($id !== ''){
            $this->db->where('id', $id)->update('kode_rekening_tarif', $data);
        }else{
            $this->db->insert('kode_rekening_tarif', $data);
            $id =  $this->db->insert_id();
        }

        return $id;
    }

    function kode_rekening_delete($id){
        $this->db->where('id', $id)->delete('kode_rekening_tarif');
    }
}
?>
