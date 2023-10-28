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
    <title>FanQie 小说TXT下载</title>
</head>
<body>
    <h1>FanQie 小说TXT下载</h1>
    
    <form action="process.php" method="post">
        <label for="directory_url">小说目录链接:</label>
        <input type="text" name="directory_url" id="directory_url" required>
        <br>
        <label for="start_chapter">起始章节:</label>
        <input type="number" name="start_chapter" id="start_chapter" required>
        <br>
        <label for="end_chapter">结束章节:</label>
        <input type="number" name="end_chapter" id="end_chapter" required>
        <br>
        <input type="submit" value="开始下载">
    </form>

    <form action="view_downloaded.php" method="post">
        <input type="submit" value="查看已下载内容">
    </form>
	<footer>
    <p>&copy; <?php echo date("Y"); ?> DreamQWQ . All rights reserved.</p>
	<p>Issues & Chat <a href="https://discord.gg/tagrkmW5
" target="_blank">Discord</a>.</p>
    <p>This website is open source on <a href="https://github.com/YunQWQ/fanqie-novel-web/" target="_blank">GitHub</a>.</p>
</footer>
</body>
</html>
