<div class="panel single">
    <div class="warp">
        <?php echo formOpen('install'); ?>
        <h1>Welcome</h1>
        <div>
            Before you can use the website, you need to configure the website.
            Mostly, it takes less than 5 minutes to complete.<br />
            First, read the agreement bellow and accept the agreement if you want to use
            this website.
        </div>
        <div class="center">
            <pre>
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR
ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
            </pre>
        </div>
        <div class="center warp">
            <?php echo inputHidden('license', 'true') ?>
            <?php echo inputCheckbox('agree', 'I have read and agree.'); ?>
        </div>
        <div class="center">
            <?php echo inputSubmit('Continue'); ?>
        </div>
        <?php echo formClose(); ?>
    </div>
</div>
