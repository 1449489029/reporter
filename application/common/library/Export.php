<?php

namespace application\common\library;


class Export
{

    // 列
    public $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];


    /**
     * 导出表结构
     *
     * @param array $tableStructureData 表结构数据
     * @return void
     */
    public function exportDataTableStructure($tableStructureData)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // 设置列宽度
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(80);

        $row = 1;
        $fieldHeaderDatas = [
            '字段名', '类型', '是否允许为空', '是否开启默认值', '默认值', '注释'
        ];

        foreach ($tableStructureData as $key => $value) {

            // 设置表名 ############################################

            // 设置表名
            $value['tableData']['COMMENT'] = !empty($value['tableData']['COMMENT']) ? $value['tableData']['COMMENT'] : '';
            $spreadsheet->getActiveSheet()->mergeCells('A' . $row . ':F' . $row)->getCell('A' . $row)->setValue($value['tableData']['NAME'] . "(" . $value['tableData']['COMMENT'] . ")");
            $spreadsheet->getActiveSheet()->getStyle('A' . $row . ':F' . $row)->getFont()->setName('宋体')->setSize(18)->setBold(true);
            $styleArray = [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $spreadsheet->getActiveSheet()->getStyle('A' . $row . ':F' . $row)->applyFromArray($styleArray);

            $row++;

            // 设置表名 ############################################


            // 设置边框
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000'],
                    ],
                ],
            ];

            $spreadsheet->getActiveSheet()->getStyle('A' . $row . ':F' . (count($value['tableFieldsData']) + count($value['tableIndexData']) + 1 + count($value['tableData']) + 1 + $row))->applyFromArray($styleArray);

            // 设置表字段 ############################################

            // 设置表头信息(字段的)
            foreach ($fieldHeaderDatas as $column => $header) {

                $spreadsheet->getActiveSheet()->getCell($this->columns[$column] . $row)->setValue($header);
                $spreadsheet->getActiveSheet()->getStyle($this->columns[$column] . $row)->getFont()->setName('宋体')->setSize(12)->setBold(true)->getColor()->setARGB('5E988C');
            }
            $styleArray = [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $spreadsheet->getActiveSheet()->getStyle('A' . $row . ':F' . $row)->applyFromArray($styleArray)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E9F3EB');
            $row++;

            //  设置字段信息
            foreach ($value['tableFieldsData'] as $sonKey => $sonValue) {
                // 设置表头信息(字段的)
                $spreadsheet->getActiveSheet()->getCell('A' . $row)->setValue($sonValue['field']);
                $spreadsheet->getActiveSheet()->getStyle('A' . $row)->getFont()->setName('宋体')->setSize(12)->setBold(true)->getColor()->setARGB('5E988C');

                $spreadsheet->getActiveSheet()->getCell('B' . $row)->setValue($sonValue['type']);
                $spreadsheet->getActiveSheet()->getStyle('B' . $row)->getFont()->setName('宋体')->setSize(12)->setBold(true)->getColor()->setARGB('5E988C');

                $spreadsheet->getActiveSheet()->getCell('C' . $row)->setValue(!empty($sonValue['null']) ? '是' : '否');
                $spreadsheet->getActiveSheet()->getStyle('C' . $row)->getFont()->setName('宋体')->setSize(12)->setBold(true)->getColor()->setARGB('5E988C');

                $spreadsheet->getActiveSheet()->getCell('D' . $row)->setValue(!empty($sonValue['detailt_status']) ? '开启' : '关闭');
                $spreadsheet->getActiveSheet()->getStyle('D' . $row)->getFont()->setName('宋体')->setSize(12)->setBold(true)->getColor()->setARGB('5E988C');

                $spreadsheet->getActiveSheet()->getCell('E' . $row)->setValue($sonValue['detailt']);
                $spreadsheet->getActiveSheet()->getStyle('E' . $row)->getFont()->setName('宋体')->setSize(12)->setBold(true)->getColor()->setARGB('5E988C');

                $spreadsheet->getActiveSheet()->getCell('F' . $row)->setValue($sonValue['common']);
                $spreadsheet->getActiveSheet()->getStyle('F' . $row)->getFont()->setName('宋体')->setSize(12)->setBold(true)->getColor()->setARGB('5E988C');

                $row++;
            }
            // 设置表字段 ############################################


            // 设置表索引 ############################################

            // 设置表索引信息
            $spreadsheet->getActiveSheet()->mergeCells('A' . $row . ':A' . ($row + (count($value['tableIndexData']))))->getCell('A' . $row)->setValue('表索引');
            $spreadsheet->getActiveSheet()->getStyle('A' . $row . ':A' . ($row + (count($value['tableIndexData']))))->getFont()->setName('宋体')->setSize(14)->setBold(true);
            // 设置水平和垂直居中
            $styleArray = [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $spreadsheet->getActiveSheet()->getStyle('A' . $row . ':F' . ($row + (count($value['tableIndexData']))))->applyFromArray($styleArray);
            // 设置背景色
            $spreadsheet->getActiveSheet()->getStyle('A' . $row . ':A' . ($row + (count($value['tableIndexData']))))->applyFromArray($styleArray)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E9F3EB');


            // 索引类型
            $spreadsheet->getActiveSheet()->mergeCells('B' . $row . ':C' . $row)->getCell('B' . $row)->setValue('索引类型');
            $spreadsheet->getActiveSheet()->getStyle('B' . $row)->getFont()->setName('宋体')->setSize(12)->setBold(true)->getColor()->setARGB('5E988C');

            // 索引名称
            $spreadsheet->getActiveSheet()->mergeCells('D' . $row . ':E' . $row)->getCell('D' . $row)->setValue('索引名称');
            $spreadsheet->getActiveSheet()->getStyle('D' . $row)->getFont()->setName('宋体')->setSize(12)->setBold(true)->getColor()->setARGB('5E988C');

            // 索引字段
            $spreadsheet->getActiveSheet()->getCell('F' . $row)->setValue('索引字段');
            $spreadsheet->getActiveSheet()->getStyle('F' . $row)->getFont()->setName('宋体')->setSize(12)->setBold(true)->getColor()->setARGB('5E988C');


            // 设置背景色
            $spreadsheet->getActiveSheet()->getStyle('B' . $row . ':F' . $row)->applyFromArray($styleArray)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E9F3EB');

            $row++;


            foreach ($value['tableIndexData'] as $indexKey => $indexValue) {
                // 索引类型
                $spreadsheet->getActiveSheet()->mergeCells('B' . $row . ':C' . $row)->getCell('B' . $row)->setValue($indexValue['type']);
                $spreadsheet->getActiveSheet()->getStyle('B' . $row)->getFont()->setName('宋体')->setSize(12)->setBold(true)->getColor()->setARGB('5E988C');

                // 索引名称
                $spreadsheet->getActiveSheet()->mergeCells('D' . $row . ':E' . $row)->getCell('D' . $row)->setValue($indexValue['name']);
                $spreadsheet->getActiveSheet()->getStyle('D' . $row)->getFont()->setName('宋体')->setSize(12)->setBold(true)->getColor()->setARGB('5E988C');

                // 索引字段
                $spreadsheet->getActiveSheet()->getCell('F' . $row)->setValue($indexValue['fieldName']);
                $spreadsheet->getActiveSheet()->getStyle('F' . $row)->getFont()->setName('宋体')->setSize(12)->setBold(true)->getColor()->setARGB('5E988C');

                $row++;
            }

            // 设置表索引 ############################################

            // 设置表信息 ############################################

            $spreadsheet->getActiveSheet()->mergeCells('A' . $row . ':A' . ($row + (count($value['tableData']))))->getCell('A' . $row)->setValue('表信息');
            $spreadsheet->getActiveSheet()->getStyle('A' . $row . ':A' . ($row + (count($value['tableData']))))->getFont()->setName('宋体')->setSize(14)->setBold(true);
            // 设置水平和垂直居中
            $styleArray = [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $spreadsheet->getActiveSheet()->getStyle('A' . $row . ':F' . ($row + (count($value['tableData']))))->applyFromArray($styleArray);

            // 设置背景色
            $spreadsheet->getActiveSheet()->getStyle('A' . $row . ':A' . ($row + (count($value['tableData']))))->applyFromArray($styleArray)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E9F3EB');


            // 配置名
            $spreadsheet->getActiveSheet()->mergeCells('B' . $row . ':C' . $row)->getCell('B' . $row)->setValue('配置名');
            $spreadsheet->getActiveSheet()->getStyle('B' . $row)->getFont()->setName('宋体')->setSize(12)->setBold(true)->getColor()->setARGB('5E988C');

            // 配置值
            $spreadsheet->getActiveSheet()->mergeCells('D' . $row . ':E' . $row)->getCell('D' . $row)->setValue('配置值');
            $spreadsheet->getActiveSheet()->getStyle('D' . $row)->getFont()->setName('宋体')->setSize(12)->setBold(true)->getColor()->setARGB('5E988C');

            // 设置背景色
            $spreadsheet->getActiveSheet()->getStyle('B' . $row . ':E' . $row)->applyFromArray($styleArray)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E9F3EB');

            $row++;

            foreach ($value['tableData'] as $tdKey => $tdValue) {

                // 配置名
                $spreadsheet->getActiveSheet()->mergeCells('B' . $row . ':C' . $row)->getCell('B' . $row)->setValue($tdKey);
                $spreadsheet->getActiveSheet()->getStyle('B' . $row)->getFont()->setName('宋体')->setSize(12)->setBold(true)->getColor()->setARGB('5E988C');

                // 配置值
                $spreadsheet->getActiveSheet()->mergeCells('D' . $row . ':E' . $row)->getCell('D' . $row)->setValue($tdValue);
                $spreadsheet->getActiveSheet()->getStyle('D' . $row)->getFont()->setName('宋体')->setSize(12)->setBold(true)->getColor()->setARGB('5E988C');

                $row++;
            }
            // 设置表信息 ############################################

            $row++;
        }


        $file_name = '数据字典.xlsx';

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename=' . $file_name . '');
        header("Content-Transfer-Encoding:binary");
        $writer->save('php://output');
        exit;
    }


    /**
     * 导出表数据
     *
     * @param array $tableData 表数据
     * @return void
     */
    public function exportTableDatas($tableData)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // 设置列宽度
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(30);

        $row = 1;
        $fieldHeaderDatas = [
            '建筑ID',
            '名称',
            '建筑类型',
            '建造所需物品',
            '建造消耗时间',
            '人口类型',
            '消耗的资源',
            '产出的资源',
            '产出资源上限',
            '解锁条件',
            '建筑组',
        ];

        foreach ($tableData as $key => $value) {

            foreach ($value as $fieldName => $fieldValue) {

            }


            $row++;
        }


        $file_name = '数据字典.xlsx';

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename=' . $file_name . '');
        header("Content-Transfer-Encoding:binary");
        $writer->save('php://output');
        exit;
    }
}
