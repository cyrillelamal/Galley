import React from 'react'
import ReactLoading from "react-loading";

class Loading extends React.Component {
    render() {
        return (
            <section className="hero is-fullheight is-info">
                <div className="hero-body">
                    <div className="container has-text-centered -align-center">
                        <div className="flex-center">
                            <ReactLoading type={'spin'} color={'#fff'}/>
                        </div>
                        <h2 className="subtitle">
                            {this.props.children}
                        </h2>
                    </div>
                </div>
            </section>
        )
    }
}

export default Loading
