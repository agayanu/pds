<?php

namespace App\Http\Controllers;

use Closure;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ViolationExport;

use function Symfony\Component\Clock\now;

class ViolationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            function (Request $request, Closure $next) {
                if(!in_array(Auth::user()->role, ['0', '1'])) {
                    return redirect()->route('home');
                }
                return $next($request);
            },
        ];
    }

    public function index(Request $r)
    {
        $dateFirst = $r->input('date_first') ?? now()->format('d-m-Y');
        $dateLast = $r->input('date_last') ?? now()->format('d-m-Y');
        return view('violation',['dateFirst' => $dateFirst, 'dateLast' => $dateLast]);
    }

    public function data(Request $r)
    {
        $dateFirstx = $r->input('date_first');
        $dateLastxx = $r->input('date_last');
        $dateFirst  = date_format(date_create($dateFirstx), 'Y-m-d');
        $dateLastx  = Carbon::create($dateLastxx)->addDay();
        $dateLast   = date_format($dateLastx, 'Y-m-d');
        $role       = Auth::user()->role;
        $username   = Auth::user()->username;
        if($r->ajax())
        {
            $data = DB::table('pds_input as a')
                ->join('master_student as b', 'a.student', '=', 'b.Reg_No')
                ->select('a.id','a.student','b.F_Name','b.Class','a.article as articleId','a.remarks','a.username','a.created_at')
                ->whereNull('a.deleted_at')
                ->whereBetween('a.created_at', [$dateFirst, $dateLast]);
            if($role == '0') {
                $data = $data->where('a.username', $username);
            }
            $dataCount = $data->count();
            $data      = $data->get();

            if(empty($dataCount))
            {
                $dataFix = [];
                return DataTables::of($dataFix)->make(true);
            }

            foreach ( $data as $d ) {
                $ca = date('d-m-Y H:i:s', strtotime($d->created_at));
                $articles = json_decode($d->articleId);
                $dt = DB::table('pds_type')
                    ->select('Group','Article','ItemDesc')
                    ->whereIn('TransNo', $articles)
                    ->get()
                    ->map(function($item) {
                        if($item->Group == 'Ringan') {
                            $item->NoArticle = '1';
                        } elseif($item->Group == 'Sedang') {
                            $item->NoArticle = '3';
                        } elseif($item->Group == 'Berat') {
                            $item->NoArticle = '5';
                        } elseif($item->Group == 'Luar Biasa') {
                            $item->NoArticle = '7';
                        } else {
                            $item->NoArticle = null;
                        }
                        return $item;
                    });
                foreach ($dt as $k => $s) {
                    if($k == 0) {
                        $article = 'Pasal '.$s->NoArticle.' ('.$s->Group.') - Nomor '.$s->Article.'. '.$s->ItemDesc;
                    } else {
                        $article = $article.' <br>Pasal '.$s->NoArticle.' ('.$s->Group.') - Nomor '.$s->Article.'. '.$s->ItemDesc;
                    }
                }
                $dataFix[] = [
                    'id'          => $d->id,
                    'nameId'      => $d->student,
                    'name'        => $d->F_Name,
                    'class'       => $d->Class,
                    'articleId'   => $d->articleId,
                    'article'     => $article,
                    'remarks'     => $d->remarks,
                    'username'    => $d->username,
                    'createdAt'   => $ca,
                ];
            }

            return DataTables::of($dataFix)
                ->addColumn('evidence', function($row){
                    $actionBtn = '
                        <a href="'.route('violation.download-evidence',['id' => $row['id']]).'" class="btn btn-sm btn-primary">Download</a>
                        ';
                    return $actionBtn;
                })
                ->addColumn('action', function($row){
                    $actionBtn = '
                        <button class="btn btn-sm btn-danger" type="button" data-coreui-toggle="modal" data-coreui-target="#del" data-coreui-name="'.$row['name'].'" data-coreui-url="'.route('violation.delete',['id' => $row['id']]).'"><i class="cil-trash" style="font-weight:bold"></i></button>
                        ';
                    return $actionBtn;
                })
                ->rawColumns(['article','evidence','action'])
                ->make(true);
        }
    }

    public function name_search(Request $r)
    {
        if($r->ajax())
        {
            $data = DB::table('master_student')
                ->select('Reg_No as id','F_Name as name','Class')
                ->where('F_Name','LIKE','%'.$r->term.'%')
                ->paginate(10, ['*'], 'page', $r->page);

            return response()->json([$data]);
        }
    }

    public function article_search(Request $r)
    {
        if($r->ajax())
        {
            $data = DB::table('pds_type')
                ->select('TransNo as id','Group','Article','ItemDesc')
                ->where('ItemDesc','LIKE','%'.$r->term.'%')
                ->paginate(10, ['*'], 'page', $r->page)
                ->through(function($item) {
                    if($item->Group == 'Ringan') {
                        $item->NoArticle = '1';
                    } elseif($item->Group == 'Sedang') {
                        $item->NoArticle = '3';
                    } elseif($item->Group == 'Berat') {
                        $item->NoArticle = '5';
                    } elseif($item->Group == 'Luar Biasa') {
                        $item->NoArticle = '7';
                    } else {
                        $item->NoArticle = null;
                    }
                    return $item;
                });

            return response()->json($data);
        }
    }

    public function store(Request $r)
    {
        $rules = [
            'name'        => 'required|integer',
            'article'     => 'required|array',
            'remarks'     => 'nullable|string',
            'evidence'    => 'required|array',
            'evidence.*'  => 'mimetypes:image/jpeg,image/png,video/quicktime,video/mp4,application/pdf,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:30048',
        ];
    
        $messages = [
            'name.required'       => 'Nama wajib diisi',
            'name.integer'        => 'Nama tidak sesuai format',
            'article.required'    => 'Pasal wajib diisi',
            'article.array'       => 'Pasal tidak sesuai format',
            'remarks.string'      => 'Catatan harus berupa teks',
            'evidence.required'   => 'Bukti wajib diisi',
            'evidence.*.mimes'    => 'Bukti hanya bisa berekstensi jpg/png/pdf/xlsx/docx',
            'evidence.*.max'      => 'Salah satu file bukti melebihi 30MB',
        ];
  
        $validator = Validator::make($r->all(), $rules, $messages);

        if($validator->fails()){
            $errorMsg = $validator->errors();
            return redirect()->back()->with('errorx', $errorMsg);
        }

        $idStudent = $r->input('name');
        $article = $r->input('article');
        $remarks = $r->input('remarks') ?? null;
        $username = Auth::user()->username;
        $nowDate = now()->format('Y-m-d');

        $hasFile = $r->hasFile('evidence');
        if($hasFile === false) { return redirect()->back()->with('error', 'Bukti Gagal Upload! Silahkan input ulang!'); }

        $articleStr = json_encode($article);
        $files = $r->file('evidence');
        $storedFiles = [];

        foreach($files as $file) {
            if(!$file->isValid()) {
                return redirect()->back()->with('error', 'Salah satu bukti korup! Silahkan upload kembali!');
            }

            $fileOriName     = $file->getClientOriginalName();
            $fileInfo        = explode(".", $fileOriName); 
            $fileExt         = end($fileInfo);
            $fileName        = $idStudent.'-'.now()->format('dmYHis').'-'.uniqid();
            $fileNameExt     = $fileName.".".$fileExt;
            $destinationFile = 'violation';
            $file->storeAs($destinationFile, $fileNameExt, 'local');
            $storedFiles[] = $fileNameExt;
        }

        $fileNamesJson = json_encode($storedFiles);

        $s = DB::table('master_student')
            ->select('ID_No','F_Name','Class','Grade','Levels','Major','Leader','LeaderName')
            ->where('Reg_No', $idStudent)
            ->first();

        $idPdsInit = DB::table('pds_init')->insertGetId([
            'TransDate' => $nowDate,
            'Reg_No'    => $idStudent,
            'ID_No'     => $s->ID_No,
            'F_Name'    => $s->F_Name,
            'Class'     => $s->Class,
            'Grade'     => $s->Grade,
            'Levels'    => $s->Levels,
            'Major'     => $s->Major,
            'Leader'    => $s->Leader,
            'LeaderName'=> $s->LeaderName,
            'UserName'  => $username,
            'LastUpdate'=> now()
        ]);

        foreach ($article as $a) {
            $t = DB::table('pds_type')
                ->select('ItemDesc','Punishment','Status')
                ->where('TransNo',$a)->first();
            DB::table('pds_init_violation')->insert([
                'ReffNo'     => $idPdsInit,
                'ItemID'     => $a,
                'ItemDesc'   => $t->ItemDesc,
                'Punishment' => $t->Punishment,
                'UserName'   => $username,
                'LastUpdate' => now()
            ]);
        }

        DB::table('pds_input')->insert([
            'reffno_pds_init'=> $idPdsInit,
            'student'        => $idStudent,
            'article'        => $articleStr,
            'remarks'        => $remarks,
            'evidence'       => $fileNamesJson,
            'username'       => $username,
            'created_at'     => now()
        ]);

        return redirect()->back()->with('success', 'Data Pelanggaran Berhasil Ditambahkan!');
    }

    public function download_evidence($id)
    {
        $i = DB::table('pds_input')->select('evidence')->where([['id', $id]]);
        if(Auth::user()->role == '0') {
            $username = Auth::user()->username;
            $i = $i->where('username', $username);
        }
        $i = $i->first();
        if(!$i) {
            return redirect()->back()->with('error', 'Data Pelanggaran Tidak Ditemukan!');
        }

        $evidences = json_decode($i->evidence);
        if(count($evidences) > 1) {
            $zip = new \ZipArchive();
            $zipFileName = 'evidence-violation-'.$id.'.zip';
            $zipFilePath = storage_path('app/private/temp/'.$zipFileName);
    
            if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
                return redirect()->back()->with('error', 'Gagal membuat file zip!');
            }
    
            foreach ($evidences as $evidence) {
                $filePath = storage_path('app/private/violation/'.$evidence);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $evidence);
                }
            }
    
            $zip->close();
    
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } else {
            $fileName = $evidences[0];
            $filePath = storage_path('app/private/violation/'.$fileName);
            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'File bukti tidak ditemukan!');
            }
    
            return response()->download($filePath);
        }
    }

    public function destroy($id)
    {
        $i = DB::table('pds_input as a')
            ->join('master_student as b', 'a.student','=','b.Reg_No')
            ->select('b.F_Name')
            ->where('a.id', $id)->first();

        DB::table('pds_input')->where('id', $id)->update([
            'deleted_at' => now()
        ]);

        return redirect()->back()->with('success', 'Data Pelanggaran '.$i->F_Name.' Berhasil Dihapus!');
    }

    public function download(Request $r)
    {
        $dateFirstx = $r->input('date_first_d');
        $dateLastxx = $r->input('date_last_d');
        $dateFirst  = date_format(date_create($dateFirstx), 'Y-m-d');
        $dateLastx  = Carbon::create($dateLastxx)->addDay();
        $dateLast   = date_format(date_create($dateLastx), 'Y-m-d');

        $data = DB::table('pds_input as a')
            ->join('master_student as b', 'a.student', '=', 'b.Reg_No')
            ->select('b.F_Name','b.Class','a.article as articleId','a.remarks','a.username','a.created_at')
            ->whereBetween('a.created_at', [$dateFirst, $dateLast])
            ->whereNull('a.deleted_at')
            ->get()
            ->map(function($d) {
                $articles = json_decode($d->articleId);
                $dt = DB::table('pds_type')
                    ->select('Group','Article','ItemDesc')
                    ->whereIn('TransNo', $articles)
                    ->get()
                    ->map(function($item) {
                        if($item->Group == 'Ringan') {
                            $item->NoArticle = '1';
                        } elseif($item->Group == 'Sedang') {
                            $item->NoArticle = '3';
                        } elseif($item->Group == 'Berat') {
                            $item->NoArticle = '5';
                        } elseif($item->Group == 'Luar Biasa') {
                            $item->NoArticle = '7';
                        } else {
                            $item->NoArticle = null;
                        }
                        return $item;
                    });
                foreach ($dt as $k => $s) {
                    if($k == 0) {
                        $article = 'Pasal '.$s->NoArticle.' ('.$s->Group.') - Nomor '.$s->Article.'. '.$s->ItemDesc;
                    } else {
                        $article = $article.'; Pasal '.$s->NoArticle.' ('.$s->Group.') - Nomor '.$s->Article.'. '.$s->ItemDesc;
                    }
                }
                return [
                    'name'      => $d->F_Name,
                    'class'     => $d->Class,
                    'article'   => $article,
                    'remarks'   => $d->remarks,
                    'username'  => $d->username,
                    'createdAt' => date('d-m-Y H:i:s', strtotime($d->created_at)),
                ];
            });

        return Excel::download(new ViolationExport($data,$dateFirstx,$dateLastxx), 'DataPelanggaran_'.$dateFirstx.'_'.$dateLastxx.'.xlsx');
    }
}
