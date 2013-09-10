<?php
namespace {
    require('libraries/simple_html_dom.php');
}


namespace Tests {
    use Parsers\DinkosParser;

    class DinkosParserText extends \PHPUnit_Framework_TestCase
    {
        protected $parser;
        protected $html;

        public function setUp()
        {

            spl_autoload_register(function ($class) {
                $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
                if (is_file('libraries/' . $class . '.php')) {
                    include 'libraries/' . $class . '.php';
                }
            });

            $this->parser = new DinkosParser();
            $this->html = file_get_html('tests/libraries/fixtures/dinkos.1.html');
        }


        public function testHtmlParser()
        {
            $expected = array(
                0 =>
                array(
                    'imgsrc' => 'http://91.68.209.9/bmi/img.dinkos.com.au/images/20/2030067581.jpg',
                    'text' => 'Kawasaki kfx400 quad with lots of extras -04',
                    'link' => 'http://www.dinkos.com.au/Kawasaki+kfx400+quad+with+lots+of+extras-1192481.htm',
                    'price' => '4,000',
                ),
                1 =>
                array(
                    'imgsrc' => '',
                    'text' => 'Scooter bargain -11',
                    'link' => 'http://www.dinkos.com.au/Scooter+bargain-1192175.htm',
                    'price' => '4,950',
                ),
                2 =>
                array(
                    'imgsrc' => '',
                    'text' => 'Piaggio X7 evo 300 scooter -11',
                    'link' => 'http://www.dinkos.com.au/Piaggio+X7+evo+300+scooter-1192172.htm',
                    'price' => '4,950',
                ),
                3 =>
                array(
                    'imgsrc' => 'http://91.68.209.12/bmi/img.dinkos.com.au/images/84/8453015365.jpg',
                    'text' => '1981 Ducati Pantah Classic Race Bike',
                    'link' => 'http://www.dinkos.com.au/1981+Ducati+Pantah+Classic+Race+Bike-1191810.htm',
                    'price' => '2,700',
                ),
                4 =>
                array(
                    'imgsrc' => 'http://91.68.209.12/bmi/img.dinkos.com.au/images/99/9932211329.jpg',
                    'text' => 'Ariel red hunter',
                    'link' => 'http://www.dinkos.com.au/Ariel+red+hunter-1191145.htm',
                    'price' => '16,500',
                ),
                5 =>
                array(
                    'imgsrc' => 'http://91.68.209.8/bmi/img.dinkos.com.au/images/36/3612806464.jpg',
                    'text' => 'Monster energy pit pro motor bike brand new -11',
                    'link' => 'http://www.dinkos.com.au/Monster+energy+pit+pro+motor+bike+brand+new-1188789.htm',
                    'price' => '1,800',
                ),
                6 =>
                array(
                    'imgsrc' => 'http://91.68.209.8/bmi/img.dinkos.com.au/images/06/0617762100.jpg',
                    'text' => 'Gsx-r1000 k8 -08',
                    'link' => 'http://www.dinkos.com.au/Gsx+r1000+k8-1152572.htm',
                    'price' => '13,500',
                ),
                7 =>
                array(
                    'imgsrc' => 'http://91.68.209.9/bmi/img.dinkos.com.au/images/92/9233606454.jpg',
                    'text' => 'Harley Davidson FLH 1200 1973',
                    'link' => 'http://www.dinkos.com.au/Harley+Davidson+FLH+1200+1973-1132863.htm',
                    'price' => '13,900',
                ),
                8 =>
                array(
                    'imgsrc' => 'http://91.68.209.11/bmi/img.dinkos.com.au/images/92/9216559816.jpg',
                    'text' => 'Harley Davidson FXD WG -93',
                    'link' => 'http://www.dinkos.com.au/Harley+Davidson+FXD+WG-1132861.htm',
                    'price' => '17,900',
                ),
                9 =>
                array(
                    'imgsrc' => 'http://91.68.209.10/bmi/img.dinkos.com.au/images/92/9208036497.jpg',
                    'text' => 'Harley Davidson 1996 FXD -86',
                    'link' => 'http://www.dinkos.com.au/Harley+Davidson+1996+FXD-1132860.htm',
                    'price' => '18,500',
                ),
                10 =>
                array(
                    'imgsrc' => 'http://91.68.209.8/bmi/img.dinkos.com.au/images/92/9225083135.jpg',
                    'text' => 'Harley Davidson EXE 1200',
                    'link' => 'http://www.dinkos.com.au/Harley+Davidson+EXE+1200-1132862.htm',
                    'price' => '14,500',
                ),
                11 =>
                array(
                    'imgsrc' => 'http://91.68.209.8/bmi/img.dinkos.com.au/images/92/9290989859.jpg',
                    'text' => 'Harley Davidson Soft Tail -89',
                    'link' => 'http://www.dinkos.com.au/Harley+Davidson+Soft+Tail-1132858.htm',
                    'price' => '16,900',
                ),
                12 =>
                array(
                    'imgsrc' => 'http://91.68.209.10/bmi/img.dinkos.com.au/images/97/9784514358.jpg',
                    'text' => 'Suzuki Bandit 1250 GSF -09',
                    'link' => 'http://www.dinkos.com.au/Suzuki+Bandit+1250+GSF-1140670.htm',
                    'price' => '9,500',
                ),
                13 =>
                array(
                    'imgsrc' => 'http://91.68.209.8/bmi/img.dinkos.com.au/images/95/9539768481.jpg',
                    'text' => 'Honda 2005 cr 250',
                    'link' => 'http://www.dinkos.com.au/Honda+2005+cr+250-1139306.htm',
                    'price' => '4,200',
                ),
                14 =>
                array(
                    'imgsrc' => 'http://91.68.209.10/bmi/img.dinkos.com.au/images/92/9242129773.jpg',
                    'text' => 'Harley Davidson FXE Super Glide',
                    'link' => 'http://www.dinkos.com.au/Harley+Davidson+FXE+Super+Glide-1132864.htm',
                    'price' => '10,000',
                ),
                15 =>
                array(
                    'imgsrc' => 'http://91.68.209.9/bmi/img.dinkos.com.au/images/92/9299513178.jpg',
                    'text' => '2006 Royal Enfield Classic 500',
                    'link' => 'http://www.dinkos.com.au/2006+Royal+Enfield+Classic+500-1132859.htm',
                    'price' => '5,900',
                ),
                16 =>
                array(
                    'imgsrc' => 'http://91.68.209.8/bmi/img.dinkos.com.au/images/90/9054472400.jpg',
                    'text' => 'SYM VS150 Scooter -11',
                    'link' => 'http://www.dinkos.com.au/SYM+VS150+Scooter-1117454.htm',
                    'price' => '3,385',
                ),
                17 =>
                array(
                    'imgsrc' => '',
                    'text' => 'Honda VTR SP1 (rc 51) -00',
                    'link' => 'http://www.dinkos.com.au/Honda+VTR+SP1+rc+51+-1128260.htm',
                    'price' => '5,000',
                ),
                18 =>
                array(
                    'imgsrc' => 'http://91.68.209.10/bmi/img.dinkos.com.au/images/79/7911197963.jpg',
                    'text' => 'Dirt Bike, Good Condition, pro circuit 2008 -11',
                    'link' => 'http://www.dinkos.com.au/Dirt+Bike+Good+Condition+pro+circuit+2008-1101955.htm',
                    'price' => '700',
                ),
                19 =>
                array(
                    'imgsrc' => 'http://91.68.209.10/bmi/img.dinkos.com.au/images/79/7968581368.jpg',
                    'text' => 'Honda crf 250 four stroke -08',
                    'link' => 'http://www.dinkos.com.au/Honda+crf+250+four+stroke-1101954.htm',
                    'price' => '4,000',
                ),
                20 =>
                array(
                    'imgsrc' => 'http://91.68.209.8/bmi/img.dinkos.com.au/images/76/7626236994.jpg',
                    'text' => 'Suzuki 1200 -96',
                    'link' => 'http://www.dinkos.com.au/Suzuki+1200-1098819.htm',
                    'price' => '5,300',
                ),
                21 =>
                array(
                    'imgsrc' => 'http://91.68.209.12/bmi/img.dinkos.com.au/images/72/7288186246.jpg',
                    'text' => 'Scooter only 4500 km,whit new cover+helmet -06',
                    'link' => 'http://www.dinkos.com.au/Scooter+only+4500+km+whit+new+cover+helmet-1096362.htm',
                    'price' => '780',
                ),
                22 =>
                array(
                    'imgsrc' => 'http://91.68.209.11/bmi/img.dinkos.com.au/images/72/7203653193.jpg',
                    'text' => 'Kawasaki GPX 250 -06',
                    'link' => 'http://www.dinkos.com.au/Kawasaki+GPX+250-1093652.htm',
                    'price' => '3,990',
                ),
                23 =>
                array(
                    'imgsrc' => '',
                    'text' => 'Yamaha scooter-silver -07',
                    'link' => 'http://www.dinkos.com.au/Yamaha+scooter+silver-1093359.htm',
                    'price' => '3,100',
                ),
                24 =>
                array(
                    'imgsrc' => '',
                    'text' => '2007 HONDA 600RR',
                    'link' => 'http://www.dinkos.com.au/2007+HONDA+600RR-1092175.htm',
                    'price' => '8,900',
                ),
                25 =>
                array(
                    'imgsrc' => '',
                    'text' => '2006 YAMAHA WR250',
                    'link' => 'http://www.dinkos.com.au/2006+YAMAHA+WR250-1092174.htm',
                    'price' => '4,500',
                ),
                26 =>
                array(
                    'imgsrc' => '',
                    'text' => '2007 HYOSUNG GTR 250',
                    'link' => 'http://www.dinkos.com.au/2007+HYOSUNG+GTR+250-1092173.htm',
                    'price' => '3,950',
                ),
                27 =>
                array(
                    'imgsrc' => '',
                    'text' => '1984 KAWASAKI GPZ9000R',
                    'link' => 'http://www.dinkos.com.au/1984+KAWASAKI+GPZ9000R-1092172.htm',
                    'price' => '3,900',
                ),
                28 =>
                array(
                    'imgsrc' => '',
                    'text' => '1991 KAWASAKI ZZR 250',
                    'link' => 'http://www.dinkos.com.au/1991+KAWASAKI+ZZR+250-1092171.htm',
                    'price' => '2,600',
                ),
                29 =>
                array(
                    'imgsrc' => '',
                    'text' => '2005 APRILLA SR50',
                    'link' => 'http://www.dinkos.com.au/2005+APRILLA+SR50-1092170.htm',
                    'price' => '1,800',
                ),
                30 =>
                array(
                    'imgsrc' => '',
                    'text' => '1976 HONDA CB750/4 K6',
                    'link' => 'http://www.dinkos.com.au/1976+HONDA+CB750+4+K6-1092060.htm',
                    'price' => '6,000',
                ),
                31 =>
                array(
                    'imgsrc' => '',
                    'text' => 'HONDA MTS 250',
                    'link' => 'http://www.dinkos.com.au/HONDA+MTS+250-1092059.htm',
                    'price' => '2,500',
                ),
                32 =>
                array(
                    'imgsrc' => '',
                    'text' => '1974 YAMAHA DT125',
                    'link' => 'http://www.dinkos.com.au/1974+YAMAHA+DT125-1091987.htm',
                    'price' => '4,000',
                ),
                33 =>
                array(
                    'imgsrc' => '',
                    'text' => 'Scooter -10',
                    'link' => 'http://www.dinkos.com.au/Scooter-1089622.htm',
                    'price' => '1,500',
                ),
                34 =>
                array(
                    'imgsrc' => 'http://91.68.209.11/bmi/img.dinkos.com.au/images/63/6339367413.jpg',
                    'text' => 'Yamaha r series 125 cc low ks road bike -09',
                    'link' => 'http://www.dinkos.com.au/Yamaha+r+series+125+cc+low+ks+road+bike-1084712.htm',
                    'price' => '5,000',
                ),
                35 =>
                array(
                    'imgsrc' => 'http://91.68.209.10/bmi/img.dinkos.com.au/images/52/5237580476.jpg',
                    'text' => 'Honda CRF450r -04',
                    'link' => 'http://www.dinkos.com.au/Honda+CRF450r-1071061.htm',
                    'price' => '3,750',
                ),
                36 =>
                array(
                    'imgsrc' => 'http://91.68.209.8/bmi/img.dinkos.com.au/images/50/5008122129.jpg',
                    'text' => 'Alpine star leathers size 42 -11',
                    'link' => 'http://www.dinkos.com.au/Alpine+star+leathers+size+42-1070650.htm',
                    'price' => '400',
                ),
                37 =>
                array(
                    'imgsrc' => 'http://91.68.209.11/bmi/img.dinkos.com.au/images/43/4301855958.jpg',
                    'text' => '2008 Honda Cbr125R',
                    'link' => 'http://www.dinkos.com.au/2008+Honda+Cbr125R-1065769.htm',
                    'price' => '2,990',
                ),
                38 =>
                array(
                    'imgsrc' => 'http://91.68.209.12/bmi/img.dinkos.com.au/images/43/4339884026.jpg',
                    'text' => 'Honda cb400 2009 -10',
                    'link' => 'http://www.dinkos.com.au/Honda+cb400+2009-1065312.htm',
                    'price' => '8,500',
                ),
                39 =>
                array(
                    'imgsrc' => 'http://91.68.209.10/bmi/img.dinkos.com.au/images/39/3994542156.jpg',
                    'text' => 'SUZUKI SV650S Quickest LAMS bike Available -10',
                    'link' => 'http://www.dinkos.com.au/SUZUKI+SV650S+Quickest+LAMS+bike+Available-1064266.htm',
                    'price' => '9,000',
                ),
                40 =>
                array(
                    'imgsrc' => '',
                    'text' => 'Scooter with carrier excellent condition -10',
                    'link' => 'http://www.dinkos.com.au/Scooter+with+carrier+excellent+condition-1052373.htm',
                    'price' => '800',
                ),
                41 =>
                array(
                    'imgsrc' => 'http://91.68.209.12/bmi/img.dinkos.com.au/images/12/1262111077.jpg',
                    'text' => 'Sweet amchair with 1100 cc of grunt yamaha xvs1100 -04',
                    'link' => 'http://www.dinkos.com.au/Sweet+amchair+with+1100+cc+of+grunt+yamaha+xvs1100-1049752.htm',
                    'price' => '8,500',
                ),
                42 =>
                array(
                    'imgsrc' => 'http://91.68.209.12/bmi/img.dinkos.com.au/images/10/1093913185.jpg',
                    'text' => 'Honda CB 250 - Recently serviced -00',
                    'link' => 'http://www.dinkos.com.au/Honda+CB+250+Recently+serviced-1045266.htm',
                    'price' => '2,500',
                ),
                43 =>
                array(
                    'imgsrc' => '',
                    'text' => 'Motorworks qw atv 250cc quad -11',
                    'link' => 'http://www.dinkos.com.au/Motorworks+qw+atv+250cc+quad-1042151.htm',
                    'price' => '1,700',
                ),
                44 =>
                array(
                    'imgsrc' => '',
                    'text' => 'Ktm 250sx excellent cond -11',
                    'link' => 'http://www.dinkos.com.au/Ktm+250sx+excellent+cond-1040360.htm',
                    'price' => '6,500',
                ),
                45 =>
                array(
                    'imgsrc' => 'http://91.68.209.11/bmi/img.dinkos.com.au/images/96/9661394451.jpg',
                    'text' => 'Honda VFR 800 -07',
                    'link' => 'http://www.dinkos.com.au/Honda+VFR+800-1039025.htm',
                    'price' => '8,200',
                ),
                46 =>
                array(
                    'imgsrc' => '',
                    'text' => '2000 BMW R1100RT',
                    'link' => 'http://www.dinkos.com.au/2000+BMW+R1100RT-1037974.htm',
                    'price' => '7,500',
                ),
                47 =>
                array(
                    'imgsrc' => 'http://91.68.209.10/bmi/img.dinkos.com.au/images/93/9398693511.jpg',
                    'text' => 'KTM 400cc 98 model',
                    'link' => 'http://www.dinkos.com.au/KTM+400cc+98+model-1037064.htm',
                    'price' => '4,200',
                ),
                48 =>
                array(
                    'imgsrc' => 'http://91.68.209.9/bmi/img.dinkos.com.au/images/87/8720080177.jpg',
                    'text' => '1996 HARLEY-DAVIDSON WIDE GLIDE Heritage Trike',
                    'link' => 'http://www.dinkos.com.au/1996+HARLEY+DAVIDSON+WIDE+GLIDE+Heritage+Trike+-1034171.htm',
                    'price' => '35,000',
                ),
                49 =>
                array(
                    'imgsrc' => 'http://91.68.209.9/bmi/img.dinkos.com.au/images/84/8466423276.jpg',
                    'text' => 'Mud Blaster 110Cc Sports Quad',
                    'link' => 'http://www.dinkos.com.au/Mud+Blaster+110Cc+Sports+Quad-1033312.htm',
                    'price' => '580',
                ),
            );

            $result = $this->parser->parse($this->html);

//        var_export($result);

            $this->assertEquals($expected, $result);

        }

    }
}