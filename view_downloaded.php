<!--
作者：云梦（YunQWQ）

版权所有（C）2023 云梦（YunQWQ）

本软件根据GNU通用公共许可证第三版（GPLv3）发布；
你可以在以下位置找到该许可证的副本：
https://www.gnu.org/licenses/gpl-3.0.html

根据GPLv3的规定，您有权在遵循许可证的前提下自由使用、修改和分发本软件。
请注意，根据许可证的要求，任何对本软件的修改和分发都必须包括原始的版权声明和GPLv3的完整文本。

本软件提供的是按 原样 提供的，没有任何明示或暗示的保证，包括但不限于适销性和特定用途的适用性。作者不对任何直接或间接损害或其他责任承担任何责任。在适用法律允许的最大范围内，作者明确放弃了所有明示或暗示的担保和条件。

免责声明：
该程序仅用于学习和研究爬虫和网页处理技术，不得用于任何非法活动或侵犯他人权益的行为。使用本程序所产生的一切法律责任和风险，均由用户自行承担，与作者和项目协作者、贡献者无关。作者不对因使用该程序而导致的任何损失或损害承担任何责任。

请在使用本程序之前确保遵守相关法律法规和网站的使用政策，如有疑问，请咨询法律顾问。

无论您对程序进行了任何操作，请始终保留此信息。
-->
<!DOCTYPE html>
<html>
<head>
    <title>已下载的内容</title>
</head>
<body>
    <h1>已下载的内容</h1>
    
    <ul>
        <?php
        $content_list = file_get_contents("downloaded_content.txt");
        $content_lines = explode("\n", $content_list);
        
        $widest_ranges = []; // 用于跟踪包含最广章节范围的项目
        $book_names = [];

        foreach ($content_lines as $content_line) {
            preg_match('/第 (\d+)~(\d+) 章 ([^ ]+)/', $content_line, $matches);
            if (count($matches) === 4) {
                $dl_start = intval($matches[1]);
                $dl_end = intval($matches[2]);
                $dl_book_name = $matches[3];

                // 检查是否包含最广章节范围的项目
                if (!isset($widest_ranges[$dl_book_name]) || ($dl_end - $dl_start) > ($widest_ranges[$dl_book_name][1] - $widest_ranges[$dl_book_name][0])) {
                    $widest_ranges[$dl_book_name] = [$dl_start, $dl_end];
                    $book_names[$dl_book_name] = $dl_book_name;
                }
            }
        }

        // 显示每个书籍的最广章节范围
        foreach ($widest_ranges as $book_name => $widest_range) {
            echo "<li><a href='http://127.0.0.1:8080/output/第 {$widest_range[0]}~{$widest_range[1]} 章 $book_name.txt' target='_blank'>第 {$widest_range[0]}~{$widest_range[1]} 章 $book_name</a></li>";
        }
        ?>
    </ul>
</body>
</html>
