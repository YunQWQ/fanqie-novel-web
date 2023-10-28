<?php
/*
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
*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 获取用户输入
    $directory_url = $_POST["directory_url"];
    $start_chapter = intval($_POST["start_chapter"]);
    $end_chapter = intval($_POST["end_chapter"]);
    // 设置请求头
    $headers = ["User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.63 Safari/537.36"];

    // 初始化cURL会话
    $ch = curl_init();

    // 设置cURL选项
    curl_setopt($ch, CURLOPT_URL, $directory_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    // 执行HTTP GET请求
    $response = curl_exec($ch);

    // 检查是否发生错误
    if ($response === false) {
        echo "cURL Error: " . curl_error($ch);
        exit;
    }

    // 关闭cURL会话
    curl_close($ch);

    // 使用DOMDocument和DOMXPath解析网页
    $dom = new DOMDocument();
    @$dom->loadHTML($response);
    $xpath = new DOMXPath($dom);

	//处理书名
	$pattern = '/<div class="info-name"><h1>(.*?)<\/h1>/s';
	if (preg_match($pattern, $response, $matches)) {
		$book_name = $matches[1];
	}
    $book_name = str_replace('/', '-', $book_name);
    $book_name = str_replace(':', '：', $book_name);
    $book_name = str_replace('?', '？', $book_name);

    // 设置文件夹地址
    $folder_path = "D:\\phpstudy_pro\\WWW\\127.0.0.1\\output\\"; // 请替换为实际的文件夹路径

    // 查找章节列表
    $chapter_items = $xpath->query('//div[@class="chapter-item"]');

// 验证起始章节和结束章节的正确性
if ($start_chapter <= 0 || $end_chapter <= 0 || $start_chapter > $end_chapter || $end_chapter > $chapter_items->length) {
    echo "起始章节或结束章节不合法。请检查输入。";
    exit;
}

$downloaded_content =  $book_name;
$content_list = file_get_contents("downloaded_content.txt");
$content_lines = explode("\n", $content_list);
$needs_download = true;

foreach ($content_lines as $content_line) {
    preg_match('/第 (\d+)~(\d+) 章 ([^ ]+)/', $content_line, $matches);
    if (count($matches) === 4) {
        $dl_start = intval($matches[1]);
        $dl_end = intval($matches[2]);
        $dl_book_name = $matches[3];

        // 编码转换
        $downloaded_content = mb_convert_encoding($downloaded_content, 'UTF-8', 'auto');
        $dl_book_name = mb_convert_encoding($dl_book_name, 'UTF-8', 'auto');

        // 检查是否需要下载
        if ($start_chapter >= $dl_start && $end_chapter <= $dl_end && $downloaded_content === $dl_book_name) {
                $needs_download = false;
                break;
        }

        // 检查是否已经下载过完全包含的章节范围
        if ($start_chapter <= $dl_start && $end_chapter >= $dl_end && $downloaded_content === $dl_book_name) {
                echo "已经下载过了";
                exit;
        }
    }
	
}


if ($needs_download) {
    // 遍历章节并下载
    if ($chapter_items->length > 0) {
        // 合并文件标题
        $merged_title = "第 $start_chapter~$end_chapter 章 $book_name";

        // 创建合并文件
        $merged_file_path = $folder_path . '/' . $merged_title . '.txt';
        $merged_file = fopen($merged_file_path, 'w');

        // 遍历每个章节
        foreach ($chapter_items as $index => $chapter_item) {
            if ($index + 1 < $start_chapter || $index + 1 > $end_chapter) {
                continue;
            }

            // 提取章节链接和标题
            $chapter_link = preg_replace('/\D/', '', $chapter_item->getElementsByTagName('a')->item(0)->getAttribute('href'));

            // 构建完整的章节链接
            $chapter_url = "https://novel.snssdk.com/api/novel/book/reader/full/v1/?device_platform=android&parent_enterfrom=novel_channel_search.tab.&aid=2329&platform_id=1&item_id=$chapter_link";

            // 初始化新的cURL会话
            $ch2 = curl_init();

            // 设置cURL选项
            curl_setopt($ch2, CURLOPT_URL, $chapter_url);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
            // 执行HTTP GET请求
            $chapter_response = curl_exec($ch2);

            // 检查是否发生错误
            if ($chapter_response === false) {
                echo "cURL Error: " . curl_error($ch2);
                exit;
            }

            // 关闭cURL会话
            curl_close($ch2);

            // 处理章节内容，保存到合并文件
            $data = json_decode($chapter_response, true);
            $content = $data["data"]["content"];
            $content = str_replace("<header><div class=\"tt-title\">", "", $content);
            $content = str_replace("</div></header><article><p>", "", $content);
            $content = str_replace("</p><p>", "\n  ", $content);
            $content = str_replace("</p></article><footer></footer>", "\n\n", $content);

            $article_section = $content;
            fwrite($merged_file, $article_section);
        }

        fclose($merged_file);
    }

    // 更新已下载的内容列表
    $downloaded_content = "第 $start_chapter~$end_chapter 章 $book_name";
    $content_list = file_get_contents("downloaded_content.txt");
    $content_list .= $downloaded_content . "\n";
    file_put_contents("downloaded_content.txt", $content_list);
	
	//重定向回去
	$redirect_url = 'view_downloaded.php'; 
	header('Location: ' . $redirect_url);
	exit;
}
else{
echo "";
echo "<li><a href='view_downloaded.php' target='_blank'>已经有人下载过了，请在下载列表中打开</a></li>";
}
}