<?php

namespace App\Helpers;

use Exception;

class NumToWords {

    public function ConvertNumber($number, $gender)
    {
        if (($number < 0) || ($number > 999999999999))
        {
            throw new Exception("العدد خارج النطاق");
        }
        $return="";
        //convert number into array of (string) number each case
        // -------number: 121210002876-----------//
        // 	0		1		2		3  //
        //'121'	  '210'	  '002'	  '876'
        // $english_format_number = number_format($number);
        $english_format_number = number_format($number);
        $array_number=explode(',', $english_format_number);
        //convert each number(hundred) to arabic
        for($i=0; $i<count($array_number); $i++){
            $place=count($array_number) - $i;
            $corrected_num = ltrim($array_number[$i], "0000");
            $corrected_num = ltrim($array_number[$i], "000");
            $corrected_num = ltrim($array_number[$i], "00");
            $corrected_num = ltrim($array_number[$i], "0");
            $return .= $this->convert($corrected_num, $place, $gender);
            if(isset($array_number[($i + 1)]) && $array_number[($i + 1)]>0) $return .= ' &#1608;';
        }
        return $return;
    }

    //private function
    private function convert($number, $place, $gender){
        // take in charge the sex of NUMBERED
        //the number word in arabic for masculine and feminine
        $words = array(
            'male'=>array(
                '0'=> '' ,'1'=> 'واحد' ,'2'=> 'اثنان' ,'3' => 'ثلاثة','4' => 'أربعة','5' => 'خمسة',
                '6' => 'ستة','7' => 'سبعة','8' => 'ثمانية','9' => 'تسعة','10' => 'عشرة',
                '11' => 'أحد عشر','12' => 'اثنا عشر','13' => 'ثلاثة عشر','14' => 'أربعة عشر','15' => 'خمسة عشر',
                '16' => 'ستة عشر','17' => 'سبعة عشر','18' => 'ثمانية عشر','19' => 'تسعة عشر','20' => 'عشرون',
                '30' => 'ثلاثون','40' => 'أربعون','50' => 'خمسون','60' => 'ستون','70' => 'سبعون',
                '80' => 'ثمانون','90' => 'تسعون', '100'=>'مئة', '200'=>'مئتان', '300'=>'ثلاثمئة', '400'=>'أربعمئة', '500'=>'خمسمئة',
                '600'=>'ستمئة', '700'=>'سبعمئة', '800'=>'ثمانمئة', '900'=>'تسعمئة'
            ),
            'female'=>array(
                '0'=> '' ,'1'=> 'واحدة' ,'2'=> 'اثنتان' ,'3' => 'ثلاث','4' => 'أربع','5' => 'خمس',
                '6' => 'ست','7' => 'سبع','8' => 'ثمان','9' => 'تسع','10' => 'عشر',
                '11' => 'إحدى عشرة','12' => 'ثنتا عشرة','13' => 'ثلاث عشرة','14' => 'أربع عشرة','15' => 'خمس عشرة',
                '16' => 'ست عشرة','17' => 'سبع عشرة','18' => 'ثمان عشرة','19' => 'تسع عشرة','20' => 'عشرون',
                '30' => 'ثلاثون','40' => 'أربعون','50' => 'خمسون','60' => 'ستون','70' => 'سبعون',
                '80' => 'ثمانون','90' => 'تسعون', '100'=>'مئة', '200'=>'مئتان', '300'=>'ثلاثمئة', '400'=>'أربعمئة', '500'=>'خمسمئة',
                '600'=>'ستمئة', '700'=>'سبعمئة', '800'=>'ثمانمئة', '900'=>'تسعمئة'
            )
        );
        //take in charge the different way of writing the thousands and millions ...
        $mil = array(
            '2'=>array('1'=>'ألف', '2'=>'ألفان', '3'=>'آلاف'),
            '3'=>array('1'=>'مليون', '2'=>'مليونان', '3'=>'ملايين'),
            '4'=>array('1'=>'مليار', '2'=>'ملياران', '3'=>'مليارات')
        );

        $mf = array('1'=>$gender, '2'=>'male', '3'=>'male', '4'=>'male');
        $number_length = strlen((string)$number);


        // echo '<br/>number  '.$number;
        // echo '<br/>number_length  '.$number_length;
        // echo '<br/>place  '.$place;

        if($number == 0) return '';
        else if($number[0]==0){
            if($number[1]==0) $number=(int)substr($number, -1);
            else $number=(int)substr($number, -2);
        }

        switch($number_length){
            case '1': {
                switch($place){
                    case '0':{
                        if($number==1) $return = 'ألف';
                    }
                        break;
                    case '1':{
                        $return = $words[$mf[$place]][$number];
                    }
                        break;
                    case '2':{

                        if($number==1) $return = 'ألف';
                        else if($number==2) $return = 'ألفان';
                        else{
                            $return = $words[$mf[$place]][$number]. ' آلاف';
                        }
                    }
                        break;
                    case '3':{
                        if($number==1) $return = 'مليون';
                        else if($number==2) $return = 'مليونان';
                        else $return = $words[$mf[$place]][$number]. ' ملايين';
                    }
                        break;
                    case '4':{
                        if($number==1) $return = 'مليار';
                        else if($number==2) $return = 'ملياران';
                        else $return = $words[$mf[$place]][$number]. ' مليارات';
                    }
                        break;
                }
            }
                break;
            case '2': {
                if(isset($words[$mf[$place]][$number])) $return = $words[$mf[$place]][$number];
                else{
                    $twoy=$number[0] * 10;
                    $ony=$number[1];
                    $return = $words[$mf[$place]][$ony].' و'.$words[$mf[$place]][$twoy];
                }
                switch($place){
                    case '2':{
                        $return .= ' ألف';
                    }
                        break;
                    case '3':{
                        $return .= ' مليون';
                    }
                        break;
                    case '4':{
                        $return .= ' مليار';
                    }
                        break;
                }
            }
                break;
            case '3':{
                if(isset($words[$mf[$place]][$number])){
                    $return = $words[$mf[$place]][$number];
                    if($number == 200) $return = 'مئتا';
                    switch($place){
                        case '2':{
                            $return .= ' ألف';
                        }
                            break;
                        case '3':{
                            $return .= ' مليون';
                        }
                            break;
                        case '4':{
                            $return .= ' مليار';
                        }
                            break;
                    }
                    return $return;
                }
                else{
                    $threey=$number[0] * 100;
                    if(isset($words[$mf[$place]][$threey])){
                        $return = $words[$mf[$place]][$threey];
                    }
                    $twoyony=$number[1] * 10 + $number[2];
                    if($twoyony==2){
                        switch($place){
                            case '1': $twoyony=$words[$mf[$place]]['2']; break;
                            case '2': $twoyony='ألفان'; break;
                            case '3': $twoyony='مليونان'; break;
                            case '4': $twoyony='ملياران'; break;
                        }
                        if($threey!=0){
                            $twoyony='و'.$twoyony;
                        }
                        $return = $return.' '.$twoyony;
                    }
                    else if($twoyony==1){
                        switch($place){
                            case '1': $twoyony=$words[$mf[$place]]['1']; break;
                            case '2': $twoyony='ألف'; break;
                            case '3': $twoyony='مليون'; break;
                            case '4': $twoyony='مليار'; break;
                        }
                        if($threey!=0){
                            $twoyony='و'.$twoyony;
                        }
                        $return = $return.' '.$twoyony;
                    }

                    else{
                        if(isset($words[$mf[$place]][$twoyony])) $twoyony = $words[$mf[$place]][$twoyony];
                        else{
                            $twoy=$number[1] * 10;
                            $ony=$number[2];
                            $twoyony = $words[$mf[$place]][$ony].' و'.$words[$mf[$place]][$twoy];
                        }
                        if($twoyony!='' && $threey!=0) $return= $return.' و'.$twoyony;
                        switch($place){
                            case '2':{
                                $return .= ' ألف';
                            }
                                break;
                            case '3':{
                                $return .= ' مليون';
                            }
                                break;
                            case '4':{
                                $return .= ' مليار';
                            }
                                break;
                        }
                    }
                }
            }
                break;
        }
        return $return;
    }

    public function NumberToWord( $num = '' )
    {
        $num    = ( string ) ( ( int ) $num );
        if( ( int ) ( $num ) && ctype_digit( $num ) )
        {
            $words  = array( );
            $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
            $list1  = array('','one','two','three','four','five','six','seven',
                'eight','nine','ten','eleven','twelve','thirteen','fourteen',
                'fifteen','sixteen','seventeen','eighteen','nineteen');

            $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
                'seventy','eighty','ninety','hundred');

            $list3  = array('','thousand','million','billion','trillion',
                'quadrillion','quintillion','sextillion','septillion',
                'octillion','nonillion','decillion','undecillion',
                'duodecillion','tredecillion','quattuordecillion',
                'quindecillion','sexdecillion','septendecillion',
                'octodecillion','novemdecillion','vigintillion');

            $num_length = strlen( $num );
            $levels = ( int ) ( ( $num_length + 2 ) / 3 );
            $max_length = $levels * 3;
            $num    = substr( '00'.$num , -$max_length );
            $num_levels = str_split( $num , 3 );

            foreach( $num_levels as $num_part )
            {

                $levels--;
                $hundreds   = ( int ) ( $num_part / 100 );
                $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
                $tens       = ( int ) ( $num_part % 100 );
                $singles    = '';

                if( $tens < 20 )
                {
                    $tens   = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
                }
                else
                {
                    $tens   = ( int ) ( $tens / 10 );
                    $tens   = ' ' . $list2[$tens] . ' ';
                    $singles    = ( int ) ( $num_part % 10 );
                    $singles    = ' ' . $list1[$singles] . ' ';
                }
                $words[]    = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );
            }

            $commas = count( $words );

            if( $commas > 1 )
            {
                $commas = $commas - 1;
            }

            $words  = implode( ', ' , $words );

            //Some Finishing Touch
            //Replacing multiples of spaces with one space
            $words  = trim( str_replace( ' ,' , ',' , $this->trim_all( ucwords( $words ) ) ) , ', ' );

            if( $commas )
            {
                $words  = $this->str_replace_last( ',' , ' and' , $words );
            }

            return $words;
        }
        else if( ! ( ( int ) $num ) )
        {
            return 'Zero';
        }
        return '';
    }

    private function trim_all( $str , $what = NULL , $with = ' ' )
    {
        if( $what === NULL )
        {
            //  Character      Decimal      Use
            //  "\0"            0           Null Character
            //  "\t"            9           Tab
            //  "\n"           10           New line
            //  "\x0B"         11           Vertical Tab
            //  "\r"           13           New Line in Mac
            //  " "            32           Space

            $what   = "\\x00-\\x20";    //all white-spaces and control chars
        }

        return trim( preg_replace( "/[".$what."]+/" , $with , $str ) , $what );
    }

    private function str_replace_last( $search , $replace , $str ) {
        if( ( $pos = strrpos( $str , $search ) ) !== false ) {
            $search_length  = strlen( $search );
            $str    = substr_replace( $str , $replace , $pos , $search_length );
        }
        return $str;
    }

    public function AfterNum ($this_n, $inthat)
    {
        if (!is_bool(strpos($inthat, $this_n)))
            return substr($inthat, strpos($inthat,$this_n)+strlen($this_n));
    }

    public function BeforeNum ($this_n, $inthat)
    {
        return substr($inthat, 0, strpos($inthat, $this_n));
    }
}
