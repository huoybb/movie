<div class="container">
    {{ form("method": "post") }}
    <div align="center">
        <input type="submit" name="Submit" value="确认删除" class="btn btn-primary form-control">
        <br>
        <input type="button" onclick="history.back(1)" name="Submit2" value="取消删除" class="btn btn-primary form-control">
    </div>
    {{ endform() }}

</div>